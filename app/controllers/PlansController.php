<?php

/**
 * Class PlansController
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class PlansController extends BaseController {

    /**
     * @var string Page title
     */
    protected $_viewTitle = 'Subscription plans';

    /**
     * @var string Plans view name
     */
    private $_plansView = 'plans';

    /**
     * @var null SubscriptionDetailsModel instance
     */
    private $_subscriptionDetailsModel = null;

    private $_plans = array();

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
        $this->_getPlans();
        $this->renderView($this->_plansView, array('plans' => $this->_plans, 'pageTitle' => $this->_viewTitle));
    }


    /**
     * Get subscription plans from database
     */
    private function _getPlans() {
        $this->_plans = $this->_subscriptionDetailsModel->getSubscriptionPlans();
    }
}