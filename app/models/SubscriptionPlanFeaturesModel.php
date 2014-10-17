<?php

/**
 * Class SubscriptionPlanFeaturesModel
 *
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class SubscriptionPlanFeaturesModel extends BaseModel {

    /**
     * @var string Table name
     */
    protected $_tableName = "SubscriptionPlanFeatures";

    /**
     * @var array Table fields
     */
    protected $_tableFields = array("SubscriptionPlanFeatureId", "SubscriptionDetailId", "Description", "IsAvailable");


    /**
     * Get features of the given subscription plan
     *
     * @param int $subscriptionPlanId
     * @return array
     * @throws Exception
     */
    public function getSubscriptionPlanFeatures($subscriptionPlanId) {
        if (!is_numeric($subscriptionPlanId)) {
            throw new Exception("Invalid subscription plan id");
        }
        $results = $this->getAll(array('Description', 'IsAvailable'), array('SubscriptionDetailId' => $subscriptionPlanId));
        return $results;
    }
}