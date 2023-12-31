<?php

namespace App\Controllers;

use App\Models\admin\Products_model;
use App\Models\Public_model;

class SmartDevices extends BaseController
{
    protected $Public_model;
    private $num_rows = 10;
    private  $url = 'http://localhost:8012/dashboard/enddevice/Enddevice.php';
    public function __construct()
    {
        $this->Public_model = new Public_model();
        helper('api_helper');

    }

public function index($page = 0)
{
    if (!session()->has('logged_user')) {
        return redirect()->to(LANG_URL . '/register');
    }

    $head = array();
    $data = array();
    $head['title'] = lang_safe('my_acc');
    $head['description'] = lang_safe('my_acc');
    $head['keywords'] = str_replace(" ", ",", $head['title']);
    $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);

    $userUID = $_SESSION['logged_user']; // Example: Fetch UID from session
    $rowscount = $this->Public_model->countSmartHomeDevicesByUID($userUID);
    $totalPages = ceil($rowscount / $this->num_rows); // Calculate total pages
    $page = max(1, min($page, $totalPages));

    $data['devices'] = $this->Public_model->getSmartHomeDevicesByUID($userUID, $this->num_rows, $page);

    $data['paginationLinks'] = '';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $page == $i ? 'active' : '';
        $data['paginationLinks'] .= "<li class='page-item $active'><a class='page-link' href='/orders/$i'>$i</a></li>";
    }

    return $this->render('smart_devices/index', $head, $data);
}

    public function add()
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('add_device');
        $head['description'] = lang_safe('add smart device');
        $head['keywords'] = str_replace(" ", ",", $head['title']);


        return $this->render('smart_devices/create_device', $head,$data); // Render the add device form view
    }
    public function store()
    {

        $validation = \Config\Services::validation(); // Load the validation library

        // Set validation rules
        $validation->setRules([
            'device_name' => 'required|max_length[16]',
            'serial_number' => 'required|exact_length[16]',
            'uid' => 'required|exact_length[32]',
            'password' => 'required|min_length[4]|max_length[10]'
        ]);

        // Check if form data is valid
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $serial = $this->request->getPost('serial_number');
        $deviceName = $this->request->getPost('device_name');
        $uid = $this->request->getPost('uid');
        $password = $this->request->getPost('password');
        $userId = $_SESSION['logged_user'];

        // Check if the user already has a device with the same serial number
        $existingDevicesCount = $this->Public_model->countSmartHomeDevicesByUserAndSerial($userId, $serial);

        if ($existingDevicesCount > 0) {
            session()->setFlashdata('error', lang_safe('duplicate_device_user','You already have a device with the same credentials.'));
            return redirect()->back()->withInput(); // Redirect back with input data
        }
        // Check if the user already has a device with the same name
        $existingNameCount = $this->Public_model->countSmartHomeDevicesByUserAndName($userId, $deviceName);
        if ($existingNameCount > 0) {
            session()->setFlashdata('error', lang_safe('duplicate_device_user','You already have a device with the same credentials.'));
            return redirect()->back()->withInput(); // Redirect back with input data
        }
        // Endpoint URL


        $passwordEncrypted=encryptData($password,'@@12@@');

        $uidEncrypted=encryptData($uid,'@@12@@');

        $deviceData = [
            'device_name'=>$deviceName,
            'user_id' => $userId,
            'UID' => $uidEncrypted,
            'serial_number' => $serial,
            'password' => $passwordEncrypted,
            'connected' => -2,
            'state' =>'none'
        ];
        $this->Public_model->saveSmartDevice($deviceData);


        // Redirect back to the form
        return redirect()->to('/smart-home');

    }

    public function refreshDeviceStatus()
    {
        $deviceId = $this->request->getPost('deviceId');
        $device = $this->Public_model->getSmartDeviceById($deviceId);
        // Call the 'get status' API
        $data = [
            'serial' => $device['serial_number'],
            'uid' => decryptData($device['UID'], '@@12@@'),
            'password' => decryptData($device['password'], '@@12@@'),
            'api' => 'get_status'
        ];
        $response = callAPI('POST', $this->url, $data);
        $responseData = json_decode($response, true);
        
        $updateData = [
            'device_id' => $deviceId,
            'connected' => $responseData['status'],
            'state' => ($responseData['status'] === 0) ? $responseData['data']['gate_position'] : 'unknown',
            'response'=>$responseData
        ];
    
        // Update the device in the database with the new status
        $this->Public_model->updateSmartDeviceStatus($updateData);

        $updateData['state'] = lang_safe('gate_position_' . $updateData['state']);
        $updateData['connection_message']=lang_safe('connection' . $responseData['status']);

        return $this->response->setJSON($updateData);
    }
    public function editDevice($deviceId)
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('edit_device');
        $head['description'] = lang_safe('edit_device_details');
        $head['keywords'] = '';

        // Fetch device details
        $device = $this->Public_model->getSmartDeviceById($deviceId);
        $device['UID']=decryptData($device['UID'],'@@12@@');
        $device['password']=decryptData($device['password'],'@@12@@');

        $data['device']=$device;
        // Check if device exists
        if (!$data['device']) {
            session()->setFlashdata('error', 'Device not found.');
            return redirect()->to('/smart-home');
        }

        return $this->render('smart_devices/edit_device', $head, $data);
    }
    public function updateDevice()
    {
        $serial = $this->request->getPost('serial_number');
        $deviceName = $this->request->getPost('device_name');
        $uid = $this->request->getPost('uid');
        $deviceID = $this->request->getPost('device_id');
        $pwd = $this->request->getPost('password');

        $validation = \Config\Services::validation();
        $userId = $_SESSION['logged_user'];
        // Set validation rules
        $validation->setRules([
            'device_name' => [
                'rules' => 'required|max_length[16]',
                'errors' => [
                    'required' => lang_safe('validation_deviceName_required'),
                    'max_length' => lang_safe('validation_deviceName_max_length'),
                ]
            ],
            'serial_number' => [
                'rules' => 'required|exact_length[16]',
                'errors' => [
                    'required' => lang_safe('validation_serialNumber_required'),
                    'exact_length' => lang_safe('validation_serialNumber_exact_length'),
                ]
            ],
            'uid' => [
                'rules' => 'required|exact_length[32]',
                'errors' => [
                    'required' => lang_safe('validation_uid_required'),
                    'exact_length' => lang_safe('validation_uid_exact_length'),
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[4]|max_length[10]',
                'errors' => [
                    'required' => lang_safe('validation_password_required'),
                    'min_length' => lang_safe('validation_password_min_length'),
                    'max_length' => lang_safe('validation_password_max_length'),
                ]
            ],
        ]);
        
    // Check serial number uniqueness
        if (!$this->Public_model->isSerialNumberUnique($userId, $serial, $deviceID)) {
            session()->setFlashdata('error', lang_safe('validation_serialNumber_is_unique'));
            return redirect()->back()->withInput();
        }

        // Check device name uniqueness
        if (!$this->Public_model->isDeviceNameUnique($userId, $deviceName, $deviceID)) {
            session()->setFlashdata('error', lang_safe('validation_deviceName_is_unique'));
            return redirect()->back()->withInput();
        }
        // Check if form data is valid
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }





        $uidEncrypted=encryptData($uid,'@@12@@');
        // Check if the user already has a device with the same serial number
        $pwdEncrypted=encryptData($pwd,'@@12@@');

        
        $deviceData = [
            'device_id' => $deviceID,
            'device_name' => $deviceName,
            'serial_number' => $serial,
            'uid' => $uidEncrypted,
            'password'=>$pwdEncrypted // Consider encryption if needed
        ];

        if ($this->Public_model->updateSmartDevice($deviceData)) {
            session()->setFlashdata('success', 'Device updated successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to update device.');
        }

        return redirect()->to('/smart-home');
    }

    public function deleteDevice($deviceId)
    {
        if ($this->Public_model->deleteSmartDevice($deviceId)) {
            // Device deleted successfully
            session()->setFlashdata('success', 'Device deleted successfully.');
        } else {
            // Error in deletion
            session()->setFlashdata('error', 'Error deleting device.');
        }

        // Redirect to the devices page or another appropriate page
        // Manually construct the redirect URL
        $redirectUrl = base_url('/smart-home'); // Adjust the URL as needed

        // Redirect using the header function
        header('Location: ' . $redirectUrl);
        exit;
    }


    public function accessControl($deviceId)
    {
        if (!session()->has('logged_user')) {
            return redirect()->to(LANG_URL . '/register');
        }
        $head = array();
        $data = array();
        $head['title'] = lang_safe('access_control');
        $head['description'] = lang_safe('manage_access');
        $head['keywords'] = '';

        // Fetch device details (Optional)
        $data['device'] = $this->Public_model->getSmartDeviceById($deviceId);

        // Fetch guests and their permissions for the device
        $data['guests'] = $this->Public_model->getGuestsForDevice($deviceId);
        // Load the view and pass data for the device and guests
        return $this->render('smart_devices/access_control', $head, $data);
    }
    public function addGuest()
    {
        $guestEmail = $this->request->getPost('user_email');
        $canControl = $this->request->getPost('can_control') == '1' ? true : false;
        $deviceId = $this->request->getPost('device_id');

        // Check if guest email exists in users_public table
        $existingUser = $this->Public_model->countPublicUsersWithEmail($guestEmail);
        if ($existingUser==0) {
            session()->setFlashdata('error', 'User with this email does not exist.');
            return redirect()->back();
        }
        // Check if the guest is already added to this device
        $isGuestAdded = $this->Public_model->isGuestAddedToDevice($guestEmail, $deviceId);
        if ($isGuestAdded) {
            session()->setFlashdata('error', 'Guest is already added to this device.');
            return redirect()->back();
        }
        // Add guest to smart_devices_guests table
        $guestData = [
            'device_id' => $deviceId,
            'email' => $guestEmail,
            'can_control' => $canControl
        ];
        if ($this->Public_model->addGuestToSmartDevice($guestData)) {
            session()->setFlashdata('success', 'Guest added successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to add guest.');
        }

        return redirect()->back();
    }
    public function deleteGuest($guestId)
    {
        if ($this->Public_model->deleteGuest($guestId)) {
            session()->setFlashdata('success', lang_safe('guest_deleted_successfully'));
        } else {
            session()->setFlashdata('error', lang_safe('error_deleting_guest'));
        }
        return redirect()->back();
    }
    public function updateGuest()
    {
        $guestId = $this->request->getPost('guest_id');
        $guestEmail = $this->request->getPost('user_email');

        $canControl = $this->request->getPost('can_control') === '1';

        $existingUser = $this->Public_model->countPublicUsersWithEmail($guestEmail);
        if ($existingUser==0) {
            session()->setFlashdata('error', 'User with this email does not exist.');
            return redirect()->back();
        }

        // Update the guest information in the database
        if ($this->Public_model->updateGuest($guestId, $guestEmail, $canControl)) {
            session()->setFlashdata('success', 'Guest updated successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to update guest.');
        }

        return redirect()->back();
    }

    // Add this function to SmartDevices controller
    public function controlDevice()
    {
        $deviceId = $this->request->getPost('deviceId');
        $action = $this->request->getPost('action');

        // Fetch device data by ID (assuming you have a method like getSmartDeviceById)
        $device = $this->Public_model->getSmartDeviceById($deviceId);
        $dataValue = '00';

        // Map the action to the corresponding data value
        switch ($action) {
            case 'open':
                $dataValue = '64';
                break;
            case 'stop':
                $dataValue = 'FF';
                break;
            // Add more cases if needed
            default:
                // For 'close' action or any other action not specified, keep the default value
                break;
        }

        // Prepare data for external API request
        $apiData = [
            'serial' => $device['serial_number'],
            'uid' => decryptData($device['UID'], '@@12@@'),
            'password' => decryptData($device['password'], '@@12@@'),
            'api' => 'control', // Change this to the actual API endpoint for device control
            'action' => '100', // Assuming action 100 is used for control_device
            'data' => $dataValue,
        ];
        // Make the API request
        $response = callAPI('POST', $this->url, $apiData);
        // Handle the response as needed
        $responseData = json_decode($response, true);

        // Send the response back to the client (optional)
        return $this->response->setJSON(['status' => $responseData['status'] , 'message' => $responseData['message']]);
    }

}
