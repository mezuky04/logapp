<?php class GetStartedController extends BaseController {

    private $_getStartedLayout = 'get-started';

    public function showGetStartedPage() {
        return View::make($this->_getStartedLayout);
    }
}