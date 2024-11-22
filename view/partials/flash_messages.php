<?php

const FLASH = 'FLASH_MESSAGES';

const FLASH_ERROR = 'danger';
const FLASH_WARNING = 'warning';
const FLASH_INFO = 'info';
const FLASH_SUCCESS = 'success';

/**
 * Create a flash message
 *
 * @param string $message
 * @param string $type
 * @return void
 */
function create_flash_message($message, $type)
{
    // remove existing message with the name
    if (isset($_SESSION[FLASH])) {
        unset($_SESSION[FLASH]);
    }
    // add the message to the session
    $_SESSION[FLASH] = ['message' => $message, 'type' => $type];
}


/**
 * Format a flash message
 *
 * @param array $flash_message
 * @return string
 */
function format_flash_message($flash_message)
{
    return sprintf(
        '<div class="alert alert-%s alert-dismissible fade show">
            %s
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>',
        $flash_message['type'],
        $flash_message['message']
    );
}

/**
 * Display a flash message
 *
 * @return void
 */
function display_flash_message()
{
    if (!isset($_SESSION[FLASH])) {
        return;
    }

    // get message from the session
    $flash_message = $_SESSION[FLASH];

    // delete the flash message
    unset($_SESSION[FLASH]);

    // display the flash message
    echo format_flash_message($flash_message);
}

/**
 * Flash a message
 *
 * @param string $message
 * @param string $type (error, warning, info, success)
 * @return void
 */
function flash($message = '', $type = '')
{
    if ($message !== '' && $type !== '') {
        // create a flash message
        create_flash_message($message, $type);
    } elseif ($message === '' && $type === '') {
        // display a flash message
        display_flash_message();
    }
}