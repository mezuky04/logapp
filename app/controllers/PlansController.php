<?php

/**
 * Class PlansController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class PlansController extends BaseController {

    /**
     * @var string View file name
     */
    protected $_viewName = 'plans';

    /**
     * @var string Page title
     */
    protected $_viewTitle = 'Subscription plans';

    /**
     * @var null SubscriptionDetailsModel instance
     */
    private $_subscriptionDetailsModel = null;

    /**
     * Create SubscriptionDetailsModel instance
     */
    public function __construct() {
        $this->_subscriptionDetailsModel = new SubscriptionDetailsModel();
    }


    /**
     * Render plans view
     */
    public function index() {
        return $this->renderView($this->_getViewData());
    }


    /**
     * @return array
     */
    private function _getViewData() {
        return array('plans' => $this->_getPlans());
    }


    /**
     * Get subscription plans from database
     */
    private function _getPlans() {
        return $this->_subscriptionDetailsModel->getSubscriptionPlans();
    }
}