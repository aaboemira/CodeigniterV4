<?php

namespace App\Controllers;

use App\Models\Public_model;

class Newsletter extends BaseController
{
    protected $Public_model;
    private $num_rows = 10;
    public function __construct()
    {
        $this->Public_model = new Public_model();

    }
    public function index()
    {
        $head = array();
        $data = array();
        $head['title'] = lang_safe('newsletter');
        $head['description'] = lang_safe('newsletter');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);

        return $this->render('newsletter', $head, $data);
    }
    public function subscribe()
    {
        $userId = $_SESSION['logged_user'];
        $this->Public_model->subscribeToNewsletter($userId);
        return redirect()->to('/newsletter')->with('success', lang_safe('subscribed_successfully'));
    }

    public function unsubscribe()
    {
        $userId = $_SESSION['logged_user'];
        $this->Public_model->unsubscribeFromNewsletter($userId);
        return redirect()->to('/newsletter')->with('success', lang_safe('unsubscribed_successfully'));
    }
}
