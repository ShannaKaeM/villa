<?php
/**
 * Twig Demo Block
 * Demonstrates using Twig templates with Carbon Fields
 */

use Carbon_Fields\Block;

Block::make(__('Twig Demo', 'blocksy-child'))
    ->set_icon('layout')
    ->set_category('twig-blocks')
    ->set_render_callback(function () {
        ?>
        <div class="twig-demo-block">
            <div class="card">
                <h3>Twig Demo Card</h3>
                <p>This is a simple static card to test if Carbon Fields blocks are working.</p>
            </div>
        </div>
        <?php
    });
