<?php
declare(strict_types=1);

namespace BzoTech\Megamenu\Block\Adminhtml\Form\Field;

use Magento\Framework\View\Element\Html\Select;

class CmsBlock extends Select
{
    /**
     * @var \Magento\Catalog\Model\Category\Attribute\Source\Page
     */
    protected $_page;

    /**
     * CmsBlock constructor.
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Catalog\Model\Category\Attribute\Source\Page $page
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Catalog\Model\Category\Attribute\Source\Page $page
    )
    {
        parent::__construct($context);
        $this->_page = $page;
    }

    /**
     * Set "name" for <select> element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Set "id" for <select> element
     *
     * @param $value
     * @return $this
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }
        return parent::_toHtml();
    }

    private function getSourceOptions(): array
    {
        return $this->_page->getAllOptions();
    }
}