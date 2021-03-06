<?php

if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array(
		'key' => 'group_5ed70f39b6df3',
		'title' => '公開設定',
		'fields' => array(
			array(
				'key' => 'field_5ed70f7aca04c',
				'label' => '公開範囲',
				'name' => 'restrict_setting',
				'type' => 'select',
				'instructions' => '',
				'required' => 1,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'all' => 'すべてのユーザーに公開',
					'restrict' => '選択したユーザーにだけ公開',
				),
				'default_value' => 'all',
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 1,
				'ajax' => 0,
				'return_format' => 'value',
				'placeholder' => '',
			),
			array(
				'key' => 'field_5ed711b2ca04d',
				'label' => 'ユーザー',
				'name' => 'restrict_user',
				'type' => 'user',
				'instructions' => '公開したいユーザーを個別で選んでください',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5ed70f7aca04c',
							'operator' => '==',
							'value' => 'restrict',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'role' => '',
				'allow_null' => 1,
				'multiple' => 1,
				'return_format' => 'id',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'series',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));
	
	acf_add_local_field_group(array(
		'key' => 'group_5ed83ad8be464',
		'title' => '公開設定(社外)',
		'fields' => array(
			array(
				'key' => 'field_5ed83b1360975',
				'label' => '公開する',
				'name' => 'not_restrict',
				'type' => 'true_false',
				'instructions' => 'オンにすると、ログインなしで閲覧できます',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '公開する',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
				),
			),
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));
	
endif;