<?php
/*------------------------------------------------------------------------

# TZ Portfolio Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2012 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

// Create a shortcut for params.
$params = &$this->item->params;
$blogItemParams = $params;
$blogItemParams -> merge(new JRegistry($this -> item -> attribs));
$images = json_decode($this->item->images);
$canEdit	= $this->item->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');
$tmpl   = null;
if($params -> get('tz_use_lightbox',1) == 1){
    $tmpl   = '&tmpl=component';
}

if($blogItemParams -> get('tz_portfolio_redirect') == 'p_article'){
    $blogLink       = JRoute::_(TZ_PortfolioHelperRoute::getPortfolioArticleRoute($this->item->slug, $this->item->catid).$tmpl);
}
else{
    $blogLink       = JRoute::_(TZ_PortfolioHelperRoute::getArticleRoute($this->item->slug, $this->item->catid).$tmpl);
}
?>

<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?>

    <?php
     if($params -> get('show_image',1) == 1 OR $params -> get('show_image_gallery',1) == 1
             OR $params -> get('show_video',1) == 1):
    ?>
        <?php

            $media          = &JModelLegacy::getInstance('Media','TZ_PortfolioModel');
            $media -> setParams($this -> mediaParams);
            $listMedia      = $media -> getMedia($this -> item -> id);
            $this -> assign('listMedia',$listMedia);
            if($blogItemParams -> get('tz_portfolio_redirect') == 'p_article'){
                $this -> assign('itemLink',$blogLink);
            }
            else{
                $this -> assign('itemLink',$blogLink);
            }
            echo $this -> loadTemplate('media');
        ?>
    <?php endif;?>

<?php if (($params->get('show_author',1)) or ($params->get('show_category',1)) or ($params->get('show_create_date',1)) or ($params->get('show_modify_date',1)) or ($params->get('show_publish_date',1)) or ($params->get('show_parent_category',1)) or ($params->get('show_hits',1))) : ?>
 <div class="TzArticleBlogInfo">
<?php endif; ?>

    <?php if ($params->get('show_create_date')) : ?>
    <span class="TzBlogCreate">
      <span class="date"> <?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2'))); ?></span>
    </span>
    <?php endif; ?>

    <?php if($params -> get('show_vote',1)):?>
        <span class="TzVote">
            <span class="TzLine">|</span>
            <span><?php echo JText::_('COM_TZ_PORTFOLIO_RATING');?></span>
            <?php echo $this -> item -> event -> TZPortfolioVote;?>
        </span>
    <?php endif;?>

    <?php if ($params->get('show_author',1) && !empty($this->item->author )) : ?>
	<span class="TzBlogCreatedby">
        <span class="TzLine">|</span>
		<?php $author =  $this->item->author; ?>
		<?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author);?>

			<?php if ($params->get('link_author') == true):?>
				<?php 	echo JText::sprintf('COM_CONTENT_WRITTEN_BY' ,
				 JHtml::_('link', JRoute::_('index.php?option=com_tz_portfolio&amp;view=users&amp;created_by='.$this -> item -> created_by), $author)); ?>

			<?php else :?>
				<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
			<?php endif; ?>
	</span>
<?php endif; ?>

    <?php if ($params->get('show_hits')) : ?>
        <span class="TzLine">|</span>
        <span class="TzBlogHits">
            <span class="numbers"><?php echo  $this->item->hits; ?></span>
            <span class="hits"><?php echo JText::_('ARTICLE_HITS'); ?></span>
        </span>
    <?php endif; ?>

<?php if ($params->get('show_parent_category') && $this->item->parent_id != 1) : ?>
        <span class="TzLine">|</span>
		<span class="TzParentCategoryName">
			<?php $title = $this->escape($this->item->parent_title);
				$url = '<a href="' . JRoute::_(TZ_PortfolioHelperRoute::getCategoryRoute($this->item->parent_id)) . '">' . $title . '</a>'; ?>
			<?php if ($params->get('link_parent_category')) : ?>
				<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
			<?php endif; ?>
		</span>
<?php endif; ?>

<?php if ($params->get('show_category',1)) : ?>
        <span class="TzLine">|</span>
		<span class="TzBlogCategory">
			<?php $title = $this->escape($this->item->category_title);
					$url = '<a href="' . JRoute::_(TZ_PortfolioHelperRoute::getCategoryRoute($this->item->catid)) . '">' . $title . '</a>'; ?>
			<?php if ($params->get('link_category',1)) : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
				<?php else : ?>
				<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
			<?php endif; ?>
		</span>
<?php endif; ?>

<?php if ($params->get('show_modify_date',1)) : ?>
    <span class="TzLine">|</span>
    <span class="TzBlogModified">
    <?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
    </span>
  <?php endif; ?>
<?php if ($params->get('show_publish_date',1)) : ?>
        <span class="TzLine">|</span>
		<span class="TzBlogPublished">
		<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
		</span>
<?php endif; ?>

<?php if($params -> get('tz_show_count_comment',1) == 1):?>
    <span class="TzLine">|</span>
    <span class="TzPortfolioCommentCount">
        <?php echo JText::_('COM_TZ_PORTFOLIO_COMMENT_COUNT');?>
        <?php if($params -> get('tz_comment_type') == 'facebook'): ?>
            <?php if(isset($this -> item -> commentCount)):?>
                <?php echo $this -> item -> commentCount;?>
            <?php endif;?>
        <?php endif;?>

        <?php if($params -> get('tz_comment_type') == 'jcomment'): ?>
            <?php
                $comments = JPATH_SITE.'/components/com_jcomments/jcomments.php';
                if (file_exists($comments)){
                    require_once($comments);
                    if(class_exists('JComments')){
                         echo JComments::getCommentsCount((int) $this -> item -> id,'com_tz_portfolio');
                    }
                }
            ?>
        <?php endif;?>
        
        <?php if($params -> get('tz_comment_type') == 'disqus'):?>
            <?php if(isset($this -> item -> commentCount)):?>
                <?php echo $this -> item -> commentCount;?>
            <?php endif;?>
        <?php endif;?>
    </span>
<?php endif;?>

<?php if (($params->get('show_author',1)) or ($params->get('show_category',1)) or ($params->get('show_create_date',1)) or ($params->get('show_modify_date',1)) or ($params->get('show_publish_date',1)) or ($params->get('show_parent_category',1)) or ($params->get('show_hits',1))) :?>
</div>
<?php endif; ?>

<?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
    <div class="TzIcon">
        <div class="btn-group pull-right"> <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <i class="icon-cog"></i> <span class="caret"></span> </a>
            <ul class="dropdown-menu">
                <?php if ($params->get('show_print_icon')) : ?>
                <li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
                <?php endif; ?>
                <?php if ($params->get('show_email_icon')) : ?>
                <li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
                <?php endif; ?>
                <?php if ($canEdit) : ?>
                <li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>

<?php if ($params->get('show_title',1)) : ?>
	<h3 class="TzBlogTitle">
		<?php if ($params->get('link_titles',1) && $params->get('access-view')) : ?>
			<a<?php if($params -> get('tz_use_lightbox') == 1) echo ' class="fancybox fancybox.iframe"';?> href="<?php echo $blogLink; ?>">
			<?php echo $this->escape($this->item->title); ?></a>
		<?php else : ?>
			<?php echo $this->escape($this->item->title); ?>
		<?php endif; ?>
        <?php if($this -> item -> featured == 1):?>
        <span class="TzFeature"><?php echo JText::_('COM_TZ_PORTFOLIO_FEATURE');?></span>
        <?php endif;?>
	</h3>
<?php endif; ?>

<?php if (!$params->get('show_intro',1)) : ?>
    <?php
        //Call event onContentAfterTitle and TZPluginDisplayTitle on plugin
        echo $this->item->event->afterDisplayTitle;
        echo $this->item->event->TZafterDisplayTitle;
    ?>
<?php endif; ?>

<?php // to do not that elegant would be nice to group the params ?>

<?php
    $extraFields    = &JModelLegacy::getInstance('ExtraFields','TZ_PortfolioModel',array('ignore_request' => true));
    $extraFields -> setState('article.id',$this -> item -> id);
    $extraFields -> setState('category.id',$this -> item -> catid);
    $extraFields -> setState('orderby',$params -> get('fields_order'));
    $extraParams    = $extraFields -> getParams();
    $itemParams     = new JRegistry($this -> item -> attribs);

    if($itemParams -> get('tz_fieldsid'))
        $extraParams -> set('tz_fieldsid',$itemParams -> get('tz_fieldsid'));

    $extraFields -> setState('params',$extraParams);
    $this -> assign('extraParams',$extraParams);
    $this -> assign('listFields',$extraFields -> getExtraFields());
?>
<?php echo $this -> loadTemplate('extrafields');?>

<?php
    //Call event onContentBeforeDisplay and onTZPluginBeforeDisplay on plugin
    echo $this->item->event->beforeDisplayContent;
    echo $this->item->event->TZbeforeDisplayContent;
?>

<?php if($this -> item -> introtext):?>
    <div class="TzDescription">
    <?php echo $this->item->introtext; ?>
    </div>
<?php endif;?>

<?php if ($params->get('show_readmore',1) && $this->item->readmore) :
	if ($params->get('access-view')) :
        $link = $blogLink;
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&amp;view=login&amp;Itemid=' . $itemId);
        
        $returnURL = $blogLink;

		$link = new JURI($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif;
?>
    <?php if($params -> get('show_readmore',1) == 1):?>
        <a class="TzReadmore<?php if($params -> get('tz_use_lightbox') == 1) echo ' fancybox fancybox.iframe';?>" href="<?php echo $link; ?>"> <i class="icon-chevron-right"></i>
        <?php if (!$params->get('access-view')) :
                echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
            elseif ($readmore = $this->item->alternative_readmore) :
                echo $readmore;
                if ($params->get('show_readmore_title', 0) != 0) :
                    echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
                endif;
            elseif ($params->get('show_readmore_title', 0) == 0) :
                echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
            else :
                echo JText::_('COM_CONTENT_READ_MORE');
                echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
            endif; ?>
        </a>
    <?php endif;?>
<?php endif; ?>

<?php
    //Call event onContentAfterDisplay and onTZPluginAfterDisplay on plugin
    echo $this->item->event->afterDisplayContent;
    echo $this->item->event->TZafterDisplayContent;
?>

<?php if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>



<div class="item-separator"></div>
