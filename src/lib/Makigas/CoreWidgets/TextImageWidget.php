<?php

/*
 * This file is part of the Makigas CoreWidgets library for WordPress.
 * Copyright (C) 2015 Dani Rodríguez <danirod@outlook.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Makigas\CoreWidgets;

defined('ABSPATH') or die('Please, do not execute this script directly.');

/**
 * This widget renders a text block and an image block. The user can choose
 * the text for the widget and the image to display, and the order of both
 * components on desktop and on phones.
 */
class TextImageWidget extends \WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'makigas-text-image',
            'description' => __('Presents a text block and an image, horizontally aligned', 'makigas-corewidgets')
        );
        parent::__construct('makigas-text-image', __('Text and Image', 'makigas-corewidgets'), $widget_ops);
    }

    /**
     * Render the widget.
     * @param array $args widget arguments
     * @param array $instance instance data
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $text = apply_filters('widget_text', empty($instance['text']) ? '' : $instance['text'], $instance, $this);
        $image = $instance['image'];

        $text_classes = 'col-md-6';
        $image_classes = 'col-md-6';

        if ('text_first' == $instance['desktop_order'] && 'image_first' == $instance['mobile_order']) {
            $text_classes .= ' col-md-pull-6';
            $image_classes .= ' col-md-push-6';
        } else if ('text_first' == $instance['mobile_order'] && 'image_first' == $instance['desktop_order']) {
            $text_classes .= ' col-md-push-6';
            $image_classes .= ' col-md-pull-6';
        } else if ('only_text' == $instance['mobile_order']) {
            $image_classes .= ' hidden-xs';
            if ('image_first' == $instance['desktop_order']) {
                $text_classes .= ' col-md-push-6';
                $image_classes .= ' col-md-pull-6';
            }
        }

        /* Build the blocks. */
        $text_block = '<div class="' . $text_classes . '">' . $text . '</div>';
        $image_block = '<div class="' . $image_classes . '"><img src="' . $image . '" style="width: 100%;" /></div>';

        /* Output widget. */
        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        echo '<div class="row">';
        if ('image_first' == $instance['mobile_order']) {
            echo $image_block . $text_block;
        } else {
            echo $text_block . $image_block;
        }
        echo '</div>';
        echo $args['after_widget'];
    }

    /**
     * Render the form.
     * @param array $inst instance data
     */
    public function form($inst) {
        $instance = wp_parse_args((array) $inst, array(
            'title' => '',
            'text' => '',
            'image' => '',
            'desktop_order' => 'text_first',
            'mobile_order' => 'only_text'
                ));
        $title = sanitize_text_field($instance['title']);
        $textarea = esc_textarea($instance['text']);
        $image = sanitize_text_field($instance['image']);
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','makigas-corewidgets'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Content:','makigas-corewidgets'); ?></label>
            <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $textarea; ?></textarea></p>

        <p><label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image:','makigas-corewidgets'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo esc_attr($image); ?>" /></p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('desktop_order')); ?>"><?php _e('Desktop:','makigas-corewidgets'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('desktop_order')); ?>" id="<?php echo esc_attr($this->get_field_id('desktop_order')); ?>" class="widefat">
                <option value="text_first"<?php selected($instance['desktop_order'], 'text_first'); ?>><?php _e('Text First', 'makigas-corewidgets'); ?></option>
                <option value="image_first"<?php selected($instance['desktop_order'], 'image_first'); ?>><?php _e('Image First', 'makigas-corewidgets'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('mobile_order')); ?>"><?php _e('Mobile:','makigas-corewidgets'); ?></label>
            <select name="<?php echo esc_attr($this->get_field_name('mobile_order')); ?>" id="<?php echo esc_attr($this->get_field_id('mobile_order')); ?>" class="widefat">
                <option value="text_first"<?php selected($instance['mobile_order'], 'text_first'); ?>><?php _e('Text First','makigas-corewidgets'); ?></option>
                <option value="image_first"<?php selected($instance['mobile_order'], 'image_first'); ?>><?php _e('Image First','makigas-corewidgets'); ?></option>
                <option value="only_text"<?php selected($instance['mobile_order'], 'only_text'); ?>><?php _e('Only Text','makigas-corewidgets'); ?></option>
            </select>
        </p>
        <?php
    }

    /**
     * Store the settings after the user has written them.
     * @param array $new_instance new instance data entered by user
     * @param array $old_instance old instance data stored
     * @return array final representation for the new settings
     */
    public function update($new_instance, $old_instance) {
        $out_instance = $old_instance;
        $out_instance['title'] = sanitize_text_field($new_instance['title']);
        $out_instance['image'] = sanitize_text_field($new_instance['image']);
        if (current_user_can('unfiltered_html')) {
            $out_instance['text'] = $new_instance['text'];
        } else {
            $out_instance['text'] = wp_kses_post(stripslashes($new_instance['text']));
        }
        if (in_array($new_instance['desktop_order'], array('text_first', 'image_first'))) {
            $out_instance['desktop_order'] = $new_instance['desktop_order'];
        } else {
            $out_instance['desktop_order'] = 'text_first';
        }
        if (in_array($new_instance['mobile_order'], array('text_first', 'image_first', 'only_text'))) {
            $out_instance['mobile_order'] = $new_instance['mobile_order'];
        } else {
            $out_instance['mobile_order'] = 'text_first';
        }
        return $out_instance;
    }

}
