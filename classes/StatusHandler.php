<?php

class StatusHandler
{
    public function setStatus($status, $message)
    {
        session_start();
        $_SESSION['status'] = ['type' => $status, 'message' => $message];
    }

    public function getStatus()
    {
        session_start();
        if (isset($_SESSION['status'])) {
            $status = $_SESSION['status'];
            unset($_SESSION['status']); // Clear after retrieving
            return $status;
        }
        return null;
    }
}
