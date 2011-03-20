<?php
/*
Plugin Name: Headway Leaf: Gravity Forms
Plugin URI: http://headwaythemes.com
Description: Adds a leaf to allow you to insert a Gravity Form wherever you choose.
Author: Clay Griffiths
Version: 1.1
Author URI: http://headwaythemes.com
*/

function gf_leaf_options($leaf){
	if($leaf['new']){
		$leaf['options']['show-form-title'] = true;
		$leaf['options']['use-ajax'] = true;
		$leaf['options']['show-form-description'] = true;
	}

	$forms = RGFormsModel::get_forms();
	
	foreach($forms as $form){
		$forms_select_options[$form->id] = $form->title;
	}

	HeadwayLeafsHelper::create_tabs(array('options' => 'Options', 'miscellaneous' => 'Miscellaneous'), $leaf['id']);
	
	//////
	
	HeadwayLeafsHelper::open_tab('options', $leaf['id']) ;
	
		HeadwayLeafsHelper::create_select(array('name' => 'form', 'value' => $leaf['options']['form'], 'label' => 'Form', 'options' => $forms_select_options), $leaf['id']);
		HeadwayLeafsHelper::create_checkbox(array('name' => 'show-form-title', 'value' => $leaf['options']['show-form-title'], 'left-label' => 'Form Title', 'checkbox-label' => 'Show Form Title'), $leaf['id']);
		HeadwayLeafsHelper::create_checkbox(array('name' => 'use-ajax', 'value' => $leaf['options']['use-ajax'], 'left-label' => 'AJAX', 'checkbox-label' => 'Submit Form With AJAX'), $leaf['id']);
		HeadwayLeafsHelper::create_checkbox(array('no-border' => true, 'name' => 'show-form-description', 'value' => $leaf['options']['show-form-description'], 'left-label' => 'Form Description', 'checkbox-label' => 'Show Form Description'), $leaf['id']);
				
	HeadwayLeafsHelper::close_tab();
	
	//////
	
	HeadwayLeafsHelper::open_tab('miscellaneous', $leaf['id']);
	
		HeadwayLeafsHelper::create_show_title_checkbox($leaf['id'], $leaf['config']['show-title']);
		HeadwayLeafsHelper::create_title_link_input($leaf['id'], $leaf['config']['leaf-title-link']);
		HeadwayLeafsHelper::create_classes_input($leaf['id'], $leaf['config']['custom-css-classes'], true);
		
	HeadwayLeafsHelper::close_tab();
}

function gf_leaf_content($leaf){
	RGForms::print_form_scripts(RGFormsModel::get_form_meta($leaf['options']['form']), $leaf['options']['use-ajax']);
	
	gravity_form($leaf['options']['form'], $leaf['options']['show-form-title'], $leaf['options']['show-form-description']);
}

function register_gf_leaf(){	
	$options = array(
			'id' => 'gravity-form',
			'name' => 'Gravity Form',
			'options_callback' => 'gf_leaf_options',
			'content_callback' => 'gf_leaf_content',
			'icon' => WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__), '', plugin_basename(__FILE__)).'icon.png',
			'show_hooks' => true
		);
	
	if(class_exists('HeadwayLeaf') && class_exists('GFCommon'))	
		$gf_leaf = new HeadwayLeaf($options);
}
add_action('init', 'register_gf_leaf');