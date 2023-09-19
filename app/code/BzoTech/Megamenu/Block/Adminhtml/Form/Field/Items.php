<?php

namespace BzoTech\Megamenu\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use BzoTech\Megamenu\Block\Adminhtml\Form\Field\Label;
use BzoTech\Megamenu\Block\Adminhtml\Form\Field\Align;
use BzoTech\Megamenu\Block\Adminhtml\Form\Field\DropdownAlign;

class Items extends AbstractFieldArray
{
    /**
     * @var
     */
    private $labelRenderer;

    /**
     * @var
     */
    private $cmsBlockRenderer;

    /**
     * @var
     */
    private $alignRenderer;

    /**
     * @var
     */
    private $dropdownAlignRenderer;

    /**
     * @var string
     */
    protected $_template = 'BzoTech_Megamenu::system/config/form/field/array.phtml';

    protected function _prepareToRender()
    {
        $this->addColumn('order_attr', ['label' => __('Sort Order')]);
        $this->addColumn('item_title', ['label' => __('Title'), 'class' => 'required-entry']);
        $this->addColumn('item_url', ['label' => __('Url'), 'class' => '']);
        $this->addColumn('item_icon', ['label' => __('Icon'), 'class' => '']);
        $this->addColumn('item_class', ['label' => __('Class'), 'class' => '']);

        $this->addColumn('item_label', [
            'label' => __('Label'),
            'renderer' => $this->getLabelRenderer()
        ]);

        $this->addColumn('item_align', [
            'label' => __('Align'),
            'renderer' => $this->getAlignRenderer()
        ]);

        $this->addColumn('item_dropdown', [
            'label' => __('Dropdown Block'),
            'renderer' => $this->getCmsBlockRenderer()
        ]);

        $this->addColumn('item_dropdown_width', ['label' => __('Dropdown Width'), 'class' => '']);

        $this->addColumn('item_dropdown_align', [
            'label' => __('Dropdown Align'),
            'renderer' => $this->getDropdownAlignRenderer()
        ]);

        $this->_addAfter       = false;
        $this->_addButtonLabel = __('Add Item');
    }

    /**
     * @param DataObject $row
     * @throws LocalizedException
     */

    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        $label = $row->getLabel();
        if ($label !== null) {
            $options['option_' . $this->getLabelRenderer()->calcOptionHash($label)] = 'selected="selected"';
        }

        $align = $row->getAlign();
        if ($align !== null) {
            $options['option_' . $this->getAlignRenderer()->calcOptionHash($align)] = 'selected="selected"';
        }

        $cmsBlock = $row->getCmsBlock();
        if ($cmsBlock !== null) {
            $options['option_' . $this->getCmsBlockRenderer()->calcOptionHash($cmsBlock)] = 'selected="selected"';
        }

        $dropdownAlign = $row->getDropdownAlign();
        if ($dropdownAlign !== null) {
            $options['option_' . $this->getAlignRenderer()->calcOptionHash($dropdownAlign)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    private function getLabelRenderer()
    {
        if (!$this->labelRenderer) {
            $this->labelRenderer = $this->getLayout()->createBlock(
                Label::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->labelRenderer;
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws LocalizedException
     */

    private function getAlignRenderer()
    {
        if (!$this->alignRenderer) {
            $this->alignRenderer = $this->getLayout()->createBlock(
                Align::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->alignRenderer;
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */

    private function getDropdownAlignRenderer()
    {
        if (!$this->dropdownAlignRenderer) {
            $this->dropdownAlignRenderer = $this->getLayout()->createBlock(
                DropdownAlign::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->dropdownAlignRenderer;
    }

    private function getCmsBlockRenderer()
    {
        if (!$this->cmsBlockRenderer) {
            $this->cmsBlockRenderer = $this->getLayout()->createBlock(
                CmsBlock::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->cmsBlockRenderer;
    }

    /**
     * @param string $columnName
     * @return string
     * @throws LocalizedException
     */

    public function renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new \Magento\Framework\Exception\LocalizedException('Wrong column name specified.');
        }
        $column    = $this->_columns[$columnName];
        $inputName = $this->_getCellInputElementName($columnName);

        if ($column['renderer']) {
            return $column['renderer']->setInputName(
                $inputName
            )->setInputId(
                $this->_getCellInputElementId('<%- _id %>', $columnName)
            )->setColumnName(
                $columnName
            )->setColumn(
                $column
            )->toHtml();
        } elseif ($columnName == 'order_attr') {
            return '<input  data-role="order"  type="hidden" id="' . $this->_getCellInputElementId(
                    '<%- _id %>',
                    $columnName
                ) .
                '"' .
                ' name="' .
                $inputName .
                '" value="<%- ' .
                $columnName .
                ' %>" ' .
                ($column['size'] ? 'size="' .
                    $column['size'] .
                    '"' : '') .
                ' class="' .
                (isset($column['class']) ? $column['class'] : 'input-text') . '"' . (isset($column['style']) ?
                    ' style="' . $column['style'] . '"' : '') . '/>';
        }
        return parent::renderCellTemplate($columnName);
    }
}