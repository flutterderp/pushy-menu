<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
// $class_sfx
?>
<ul class="pushy-main-submenu no-bullet"<?php
	$tag = '';

	if ($params->get('tag_id') != null)
	{
		$tag = $params->get('tag_id') . '';
		echo ' id="' . $tag . '"';
	}
?>>
<?php
foreach ($list as $i => &$item)
{
	$class = 'item-' . $item->id;

	if (($item->id == $active_id) OR ($item->type == 'alias' AND $item->params->get('aliasoptions') == $active_id))
	{
		$class .= ' current';
	}

	if (in_array($item->id, $path))
	{
		$class .= ' active';
	}
	elseif ($item->type == 'alias')
	{
		$aliasToId = $item->params->get('aliasoptions');

		if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
		{
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path))
		{
			$class .= ' alias-parent-active';
		}
	}

	if ($item->type == 'separator')
	{
		$class .= ' divider';
	}

	if ($item->deeper)
	{
		$class .= ' deeper';
	}

	if ($item->parent)
	{
		$class .= ' parent';
	}

	if (!empty($class))
	{
		$class = ' class="' . trim($class) . '"';
	}

	echo '<li' . $class . '>';

	// Render the menu item.
	renderItem($item->type, $item);

	// The next item is deeper.
	if ($item->deeper)
	{
		echo '<ul class="pushy-submenu no-bullet">';
		// Render the parent menu item.
		echo '<li' . $class . '>';
		// Reset $item->deeper so we don't get a chevron on the submenu
		$item->deeper = 0;
		
		renderItem($item->type, $item);
		
		echo '</li>';
		// End parent menu item
	}
	elseif ($item->shallower)
	{
		// The next item is shallower.
		echo '</li>';
		echo str_repeat('</ul><div class="pushy-close-submenu"></div></li>', $item->level_diff);
	}
	else
	{
		// The next item is on the same level.
		echo '</li>';
	}
}
?>
</ul>
<?php
/**
 * Method for rendering a menu item
 * 
 * @param string	item_type	The menu item's type property
 * @param object	item			The item object itself
 */
function renderItem($item_type, $item)
{
	switch ($item_type) :
		case 'separator':
		case 'url':
		case 'component':
		case 'heading':
			require JModuleHelper::getLayoutPath('mod_menu', 'pushy_' . $item_type);
			break;
		
		default:
			require JModuleHelper::getLayoutPath('mod_menu', 'pushy_url');
			break;
	endswitch;
}