<?php
require_once('Customizing/global/plugins/Modules/Cloud/CloudHook/OneDrive/vendor/autoload.php');
/**
 * Class ilOneDrivePlugin
 *
 * @author  Fabian Schmid <fs@studer-raimann.ch>
 * @version 1.0.0
 */
class ilOneDrivePlugin extends ilCloudHookPlugin  {

	const PLUGIN_NAME = 'OneDrive';
	/**
	 * @var exodAppBusiness
	 */
	protected static $app_instance;


	/**
	 * @return string
	 */
	public function getPluginName() {
		return self::PLUGIN_NAME;
	}

    /**
	 * @param exodBearerToken $exodBearerToken
	 *
	 * @return exodAppBusiness|exodAppPublic
	 */
	public function getExodApp(exodBearerToken $exodBearerToken) {
		$exodConfig = new exodConfig();
		$exodConfig->checkComplete();

		exodCurl::setSslVersion($exodConfig->getSSLVersion());
		exodCurl::setIpV4($exodConfig->getResolveIpV4());

		if ($exodConfig->getClientType() == exodApp::TYPE_BUSINESS) {
			$exodTenant = new exodTenant();
			$exodTenant->setTenantId($exodConfig->getTentantId());
			$exodTenant->setTenantName($exodConfig->getTenantName());

			$app = exodAppBusiness::getInstance($exodBearerToken, $exodConfig->getClientId(), $exodConfig->getClientSecret(), $exodTenant);
			$app->setIpResolveV4($exodConfig->getResolveIpV4());
		} elseif ($exodConfig->getClientType() == exodApp::TYPE_PUBLIC) {
			$app = exodAppPublic::getInstance($exodBearerToken, $exodConfig->getClientId(), $exodConfig->getClientSecret());
			$app->setIpResolveV4($exodConfig->getResolveIpV4());
		}

		return $app;
	}


	/**
	 * @var ilOneDrivePlugin
	 */
	protected static $instance;


	/**
	 * @return ilOneDrivePlugin
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}


    /**
     * @param $user ilObjUser
     *
     * @return string|null
     * @throws ilCloudPluginConfigException
     */
	public function getOneDriveEmailForUser($user)
    {
        OneDriveEmailBuilderFactory::getInstance()->getEmailBuilder()->getOneDriveEmailForUser($user);

    }

	/**
	 * @return string
	 */
	public function getCsvPath() {
		return './Customizing/global/plugins/Modules/Cloud/CloudHook/OneDrive/lang/lang.csv';
	}


	/**
	 * @return string
	 */
	public function getAjaxLink() {
		return null;
	}
}
