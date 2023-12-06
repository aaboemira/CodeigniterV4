<?php

namespace App\Controllers;

use App\Models\admin\Products_model;
use App\Models\Public_model;

class SmartDevices extends BaseController
{
    protected $Public_model;
    private $num_rows = 10;
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



        $devices = $this->Public_model->getSmartHomeDevicesByUID($userUID,$this->num_rows,$page);
        foreach ($devices as &$device) {
            // Prepare the data for API request
            $data = [
                'serial' => $device['serial_number'],
                'uid' => decryptData($device['UID'], '@@12@@'), // Assuming you have decryption function
                'password' => decryptData($device['password'], '@@12@@'),
                'api' => 'get_status'
            ];
            // Call the API
            $url = 'http://localhost:8012/dashboard/enddevice/Enddevice.php';
            $response = callAPI('POST', $url, $data);
            $responseData = json_decode($response, true);

            // Update device data based on the response
            if ($responseData['status'] === 0) {
                $device['connected'] = 1;
                $device['state'] = $responseData['data']['gate_position_desc'];
            } else {
                $device['connected'] = 0;
                $device['state'] = 'none';
            }

            // Update the device in the database
            $this->Public_model->updateSmartDeviceStatus($device);
        }
        $data['devices']=$devices;


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

        // Endpoint URL
        $url = 'http://localhost:8012/dashboard/enddevice/Enddevice.php';

        // Prepare the data
        $data = [
            'serial' => $serial,
            'uid' => $uid,
            'password' => $password,
            'api' => 'get_status'
        ];
        $response = callAPI('POST', $url, $data);
        $responseData = json_decode($response, true);

        $passwordEncrypted=encryptData($password,'@@12@@');

        $uidEncrypted=encryptData($password,'@@12@@');

        $deviceData = [
            'device_name'=>$deviceName,
            'user_id' => $userId,
            'UID' => $uidEncrypted,
            'serial_number' => $serial,
            'password' => $passwordEncrypted,
            'connected' => $responseData['status'] === 0 ? 1 : 0,
            'state' => $responseData['status'] === 0 ? $responseData['data']['gate_position_desc'] : 'none'
        ];
        // Handle API response
        if ($responseData['status'] === 0) {
            // Success: Set isConnected = 1 and State = Gate Description
            $this->Public_model->saveSmartDevice($deviceData);
            session()->setFlashdata('success', 'Device status retrieved successfully!');
        } elseif ($responseData['status'] < 100) {
            // Error: Set isConnected = 0 and State = 'Error'
            $this->Public_model->saveSmartDevice($deviceData);
            $errorMessage = isset($responseData['message']) ? $responseData['message'] : 'An error occurred.';
            session()->setFlashdata('error', $errorMessage);
        } else {
            // Other errors
            session()->setFlashdata('error', 'Unable to add this device');
        }

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
        $response = callAPI('POST', 'http://localhost:8012/dashboard/enddevice/Enddevice.php', $data);
        $responseData = json_decode($response, true);
        $updateData = ['device_id' => $deviceId];
        if ($responseData['status'] === 0) {
            $updateData['connected'] = 1;
            $updateData['state'] = $responseData['data']['gate_position_desc'];
        } else {
            $updateData['connected'] = 0;
            $updateData['state'] = 'none';
        }
        $this->Public_model->updateSmartDeviceStatus($updateData);

        // Return the updated status
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
        $deviceData = [
            'device_id' => $this->request->getPost('device_id'),
            'device_name' => $this->request->getPost('device_name'),
            'serial_number' => $this->request->getPost('serial_number'),
            'uid' => encryptData($this->request->getPost('uid'),'@@12@@'), // Consider encryption if needed
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
}
