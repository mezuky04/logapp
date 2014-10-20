<?php

/**
 * Class SubscriptionDetailsModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class SubscriptionDetailsModel extends BaseModel {

    /**
     * @var string Table name
     */
    protected $_tableName = 'SubscriptionDetails';

    /**
     * @var array Table fields
     */
    protected $_tableFields = array('SubscriptionDetailId', 'Name', 'Key', 'Price', 'Period', 'Special');

    /**
     * @var string
     */
    protected $_subscriptionPlanFeaturesTable = 'SubscriptionPlanFeatures';


    /**
     * Get all subscription plans
     *
     * @return mixed
     */
    public function getSubscriptionPlans() {

        $subscriptionPlans = $this->getAll(array('SubscriptionDetailId', 'Name', 'Key', 'Price', 'Period', 'Special'));
        if (!count($subscriptionPlans)) {
            return false;
        }
        $subscriptionPlansFeaturesModel = new SubscriptionPlanFeaturesModel();
        foreach ($subscriptionPlans as $subscriptionPlan) {
            $subscriptionPlan->Items = $subscriptionPlansFeaturesModel->getSubscriptionPlanFeatures($subscriptionPlan->SubscriptionDetailId);
        }
        return (array) $subscriptionPlans;
    }


    public function getNameByKey($key) {
        $result = $this->getOne(array('Name'), array('Key' => $key));
        return $result->Name;
    }


    /**
     * Check if given plan key is an exiting subscription plan
     *
     * @param string $planKey
     * @return bool
     */
    public function isSubscriptionPlan($planKey) {
        if (!$this->getOne(array('SubscriptionDetailId'), array('Key' => $planKey))) {
            return false;
        }
        return true;
    }


    /**
     * Check if the given subscription plan is valid
     *
     * @param string $planKey
     * @return array
     */
    public function validateSubscriptionPlan($planKey) {
        if (!$this->getOne(array('SubscriptionDetailId'), array('Key' => $planKey))) {
            return array(
                'subscriptionPlanError' => true,
                'invalidSubscriptionPlan' => true
            );
        }
    }
}