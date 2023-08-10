<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Pwa
 * @author    Webkul
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\Pwa\Block\System\Config;

class SupportLinks extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Constructor
     *
     * @param \Magento\Framework\Component\ComponentRegistrarInterface $componentRegistrar
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Component\ComponentRegistrarInterface $componentRegistrar,
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->componentRegistrar = $componentRegistrar;
        $this->readFactory = $readFactory;
        $this->jsonHelper = $jsonHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get Module Current version
     *
     * @param string $moduleName
     * @return string
     */
    public function getModuleVersion($moduleName)
    {
        $path = $this->componentRegistrar->getPath(
            \Magento\Framework\Component\ComponentRegistrar::MODULE,
            $moduleName
        );
        $directoryRead = $this->readFactory->create($path);
        $composerJsonData = $directoryRead->readFile('composer.json');
        $data = $this->jsonHelper->jsonDecode($composerJsonData);

        return !empty($data['version']) ? $data['version'] : __('Read error!');
    }
    
    /**
     * Get Html Element
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $moduleCode = 'Webkul_Pwa';

        $html = '<div class="orderbywhatsapp-version">
        <p>'.__("Author").': 
        <a target="_blank" title="Webkul Software Private Limited" href="https://webkul.com/">Webkul</a></p>
        <p>'.__("Version").': '.$this->getModuleVersion($moduleCode).'</p>
        <p>'.__("User Guide").': 
        <a target="_blank"
         href="https://webkul.com/blog/progressive-web-application-magento2/">Click Here</a></p>
        <p>'.__("Store Extension").': 
        <a target="_blank"
         href="https://store.webkul.com/magento2-progressive-web-app.html">Click Here</a></p>
        <p>'.__("Ticket/Customisations").': 
        <a target="_blank" href="https://webkul.uvdesk.com/en/customer/create-ticket/">Click Here</a></p>
        <p>'.__("Our Services").': 
        <a target="_blank" href="https://webkul.com/magento-development/">Click Here</a></p></div>';
        return $html;
    }
}
