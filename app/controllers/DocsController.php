<?php

/**
 * Class DocsController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class DocsController extends BaseController {

    /**
     * @var string Error codes layout name
     */
    private $_errorCodesLayout = 'error-codes';

    /**
     * @var string SDKs layout name
     */
    private $_sdksLayout = 'sdks';

    /**
     * Render error codes page
     *
     * @return mixed
     */
    public function showErrorCodesPage() {
        // Get errors from database
        return View::make($this->_errorCodesLayout);
    }


    /**
     * Render SDKs page
     *
     * @return mixed
     */
    public function showSDKsPage() {
        return View::make($this->_sdksLayout);
    }
}