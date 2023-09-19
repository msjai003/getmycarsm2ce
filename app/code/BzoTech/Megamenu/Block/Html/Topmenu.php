<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace BzoTech\Megamenu\Block\Html;
/**
 * Html page top menu block
 *
 * @api
 * @since 100.0.2
 */

use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\Node\Collection;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{
    /**
     * @param string $value
     * @return mixed
     */
    public function getContent($value = '')
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $filterProvider = $objectManager->create('\Magento\Cms\Model\Template\FilterProvider');

        $htmlContent = $filterProvider->getPageFilter()->filter($value);
        return $htmlContent;
    }

    /**
     * @param Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string
     */

    protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit)
    {
        $html     = '';
        $colStops = [];
        if ($childLevel == 0 && $limit) {
            $colStops = $this->_columnBrake($child->getChildren(), $limit);
        }

        $enableMegamenu     = $this->getCategory($child)->getEnableMegamenu();
        $type               = "";
        $colLeftWidthStyle  = "";
        $colRightWidthStyle = "";
        $colCateWidthStyle  = "";
        $dropdownWidthStyle = "";
        $colLeftWidth       = 0;
        $colRightWidth      = 0;
        $colCateWidth       = 0;
        $contentTop         = $this->getContent($this->getCategory($child)->getContentTop());
        $contentLeft        = $this->getContent($this->getCategory($child)->getContentLeft());
        $contentRight       = $this->getContent($this->getCategory($child)->getContentRight());
        $contentBottom      = $this->getContent($this->getCategory($child)->getContentBottom());
        $dropdownWidth      = $this->getCategory($child)->getDropdownWidth();
        $enableTop          = $this->getCategory($child)->getEnableContentTop();
        $enableLeft         = $this->getCategory($child)->getEnableContentLeft();
        $enableRight        = $this->getCategory($child)->getEnableContentRight();
        $enableBottom       = $this->getCategory($child)->getEnableContentBottom();
        $catColumn          = $this->getCategory($child)->getNumberCatColumn();
        $catColumnClass     = "";

        if ($childLevel == 0) {
            if ($enableMegamenu) {
                $type = "type-megamenu";
            } else {
                $type = "type-default";
            }

            if ($dropdownWidth && $enableMegamenu) {
                $dropdownWidthStyle = "width: " . $dropdownWidth;
            }

            if ($catColumn && $enableMegamenu) {
                $catColumnClass = "cate-" . $catColumn . "-col";
            }
        }

        if ($enableLeft && $contentLeft && $enableMegamenu) {
            $colLeftWidth      = $this->getCategory($child)->getColLeftWidth();
            $colLeftWidthStyle = "width:" . $colLeftWidth . "%";
        }

        if ($enableRight && $contentRight && $enableMegamenu) {
            $colRightWidth      = $this->getCategory($child)->getColRightWidth();
            $colRightWidthStyle = "width:" . $colRightWidth . "%";
        }

        $colCateWidth      = 100 - $colLeftWidth - $colRightWidth;
        $colCateWidthStyle = "width:" . $colCateWidth . "%";

        if (!$child->hasChildren()) {
            if (!$enableMegamenu || (!$enableTop && !$enableLeft && !$enableRight && !$enableBottom)) {
                return $html;
            }
        }

        $html .= '<div class="dropdowm-megamenu ' . $catColumnClass . ' ' . $type . '" style="' . $dropdownWidthStyle . '">'; // div dropdown

        if ($enableTop && $enableMegamenu && $childLevel == 0) {
            $html .= '<div class="megamenu-content-top">' . $contentTop . '</div>';
        }

        if ($enableLeft || $enableRight || $child->hasChildren()) {
            $html .= '<div class="megamenu-middle middle-level-' . $childLevel . '">'; // div middle

            if ($enableLeft && $enableMegamenu && $childLevel == 0) {
                $html .= '<div class="megamenu-content-left" style="' . $colLeftWidthStyle . '">' . $contentLeft . '</div>';
            }

            if ($child->hasChildren()) {
                $html .= '<div class="megamenu-content-cate" style="' . $colCateWidthStyle . '">';
                $html .= '<ul class="level' . $childLevel . ' ' . $childrenWrapClass . '">';
                $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
                $html .= '</ul>';
                $html .= '</div>';
            }

            if ($enableRight && $enableMegamenu && $childLevel == 0) {
                $html .= '<div class="megamenu-content-right" style="' . $colRightWidthStyle . '">' . $contentRight . '</div>';
            }

            $html .= '</div>'; // end div middle
        }

        if ($enableBottom && $enableMegamenu && $childLevel == 0) {
            $html .= '<div class="megamenu-content-bottom">' . $contentBottom . '</div>';
        }

        $html .= '</div>'; // end div dropdown

        return $html;
    }

    /**
     * @param int $parentLevel
     * @return int
     */
    private function getChildLevel($parentLevel): int
    {
        return $parentLevel === null ? 0 : $parentLevel + 1;
    }

    /**
     * @param Collection $children
     * @param int $childLevel
     */

    private function removeChildrenWithoutActiveParent(Collection $children, int $childLevel): void
    {
        /** @var Node $child */
        foreach ($children as $child) {
            if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
                $children->delete($child);
            }
        }
    }

    /**
     * @param array $colBrakes
     * @param int $counter
     * @return bool
     */

    private function shouldAddNewColumn(array $colBrakes, int $counter): bool
    {
        return count($colBrakes) && $colBrakes[$counter]['colbrake'];
    }

    /**
     * @param $category
     * @return mixed
     */

    public function getCategory($category)
    {
        $objectManager   = \Magento\Framework\App\ObjectManager::getInstance();
        $categoryFactory = $objectManager->create('\Magento\Catalog\Model\CategoryFactory');
        $categoryNote    = strpos($category->getId(), 'category-node-') + strlen('category-node-');
        $catId           = substr($category->getId(), $categoryNote);
        $cate            = $categoryFactory->create()->load($catId);
        return $cate;
    }

    /**
     * @param Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     * @return string
     */

    protected function _getHtml(
        Node $menuTree,
        $childrenWrapClass,
        $limit,
        array $colBrakes = []
    )
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helper        = $objectManager->get('\BzoTech\Megamenu\Helper\Data');
        $limitItem     = (int)$helper->getMenuConfig('primary_secondary_menu/primary_cfg/vertical_limit');
        $menuType      = $helper->getMenuConfig('primary_secondary_menu/megamenu_type');
        $html          = '';
        $hiddenData    = '';
        $i             = 0;
        $children      = $menuTree->getChildren();
        $childLevel    = $this->getChildLevel($menuTree->getLevel());
        $this->removeChildrenWithoutActiveParent($children, $childLevel);

        $counter                 = 1;
        $childrenCount           = $children->count();
        $parentPositionClass     = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        /** @var Node $child */
        foreach ($children as $child) {
            $itemLabelHtml = '';
            $child->setLevel($childLevel);
            $child->setIsFirst($counter === 1);
            $child->setIsLast($counter === $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass     = $menuTree->getOutermostClass();

            if ($childLevel === 0 && $outermostClass) {
                $i++;
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $this->setCurrentClass($child, $outermostClass);

                if ($i > $limitItem && $menuType == 'vertical') {
                    $hiddenData = 'data-hidden="true"';
                }
            }

            if ($this->shouldAddNewColumn($colBrakes, $counter)) {
                $html .= '</ul></li><li class="column"><ul>';
            }

            $customUrlConfig = $this->getCategory($child)->getCustomUrl();
            $catLinkUrl      = $child->getUrl();

            if ($customUrlConfig) {
                if (strpos($customUrlConfig, 'http://') !== false || strpos($customUrlConfig, 'https://') !== false) {
                    $catLinkUrl = $customUrlConfig;
                } else if ($customUrlConfig == '/') {
                    $catLinkUrl = $this->getUrl();
                } else {
                    $catLinkUrl = $this->getUrl() . $customUrlConfig;
                }
            }

            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . ' ' . $hiddenData . ' ' . '>';
            $html .= '<a href="' . $objectManager->create('Magento\Framework\Escaper')->escapeHtml($catLinkUrl) . '" ' . $outermostClassCode . '>' . $this->getIcon($child) . '<span class="cat-name">' . $this->escapeHtml(
                    $child->getName()
                ) . '</span>' . $this->getLabel($child) . '</a>' . $this->_addSubMenu(
                    $child,
                    $childLevel,
                    $childrenWrapClass,
                    $limit
                ) . '</li>';
            $counter++;
        }

        if (is_array($colBrakes) && !empty($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        return $html;
    }

    /**
     * @param $child
     * @return string
     */

    public function getLabel($categoryNote)
    {

        $itemLabel     = $this->getCategory($categoryNote)->getItemLabel();
        $itemLabelHtml = '';
        switch ($itemLabel) {
            case "new":
                $itemLabelHtml = '<span class="item-label ' . $itemLabel . '-menu-item">' . __("New") . '</span>';
                break;
            case "hot":
                $itemLabelHtml = '<span class="item-label ' . $itemLabel . '-menu-item">' . __("Hot") . '</span>';
                break;
            case "sale":
                $itemLabelHtml = '<span class="item-label ' . $itemLabel . '-menu-item">' . __("Sale") . '</span>';
                break;
            case "featured":
                $itemLabelHtml = '<span class="item-label ' . $itemLabel . '-menu-item">' . __("Featured") . '</span>';
                break;
        }
        return $itemLabelHtml;
    }

    /**
     * @param $categoryNote
     * @return string
     */
    public function getIcon($categoryNote)
    {
        $objectManager  = \Magento\Framework\App\ObjectManager::getInstance();
        $helper         = $objectManager->get('\BzoTech\Megamenu\Helper\Data');
        $mediaUrl       = $helper->getMediaUrl();
        $itemIconConfig = $this->getCategory($categoryNote)->getIconBefore();
        $itemIconHtml   = '';

        if ($itemIconConfig) {
            $iconString   = strpos($itemIconConfig, '/media/') + strlen('/media/');
            $itemIconPath = $mediaUrl . substr($itemIconConfig, $iconString);
            $itemIconHtml = '<span class="item-icon"><img src="' . $itemIconPath . '" /></span>';
        }

        return $itemIconHtml;
    }

    /**
     * @param Node $item
     * @return array
     */
    protected function _getMenuItemClasses(Node $item)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $helper        = $objectManager->get('\BzoTech\Megamenu\Helper\Data');
        $menuType      = $helper->getMenuConfig('primary_secondary_menu/megamenu_type');
        $itemCenter    = (int)$helper->getMenuConfig('primary_secondary_menu/primary_cfg/item_center');
        $customUrl     = $this->getCategory($item)->getCustomUrl();

        $classes = [
            'level' . $item->getLevel(),
            $item->getPositionClass(),
        ];

        if ($item->getIsCategory()) {
            $classes[] = 'category-item';
        }

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        } elseif ($item->getHasActive()) {
            $classes[] = 'has-active';
        }

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->getLevel() == 0) {
            $enableTop    = $this->getCategory($item)->getEnableContentTop();
            $enableLeft   = $this->getCategory($item)->getEnableContentLeft();
            $enableRight  = $this->getCategory($item)->getEnableContentRight();
            $enableBottom = $this->getCategory($item)->getEnableContentBottom();
            $itemRight    = $this->getCategory($item)->getFloatRight();


            if ($item->hasChildren() || ($this->getCategory($item)->getEnableMegamenu() && ($enableTop || $enableLeft || $enableRight || $enableBottom))) {
                $classes[] = 'parent';
            }

            $classes[] = $this->getCategory($item)->getDropdownAlign();

            if (!$itemCenter && $menuType == "horizontal") {
                if ($itemRight) {
                    $classes[] = 'item-right';
                } else {
                    $classes[] = 'item-left';
                }
            }
        }

        if ($item->getLevel() > 0) {
            if ($item->hasChildren()) {
                $classes[] = 'parent';
            }
        }

        $classes[] = $objectManager->create('Magento\Framework\Escaper')->escapeHtml($this->getCategory($item)->getCustomClass());

        if ($customUrl) {
            $classes[] = 'custom-url';
        }

        return $classes;
    }
}
