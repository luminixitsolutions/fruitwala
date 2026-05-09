<?php
declare(strict_types=1);

/**
 * Admin "Home page" editor: page slug => form blocks.
 *
 * @return array<string, array{title: string, blocks: list<array{legend: string, fields: list<array{section_key: string, field_key: string, label: string, type: string, hint?: string}>}>, hero_slides_ui?: bool, list_ui?: string}>
 */
function fruitwala_admin_home_edit_sections(): array
{
    return [
        'hero' => [
            'title' => 'Hero / banner (slider)',
            'blocks' => [],
            'hero_slides_ui' => true,
        ],
        'reels' => [
            'title' => 'Featured reels',
            'blocks' => [],
            'list_ui' => 'reels',
        ],
        'quality' => [
            'title' => 'Why choose (quality section)',
            'blocks' => [
                [
                    'legend' => 'Section header & center video',
                    'fields' => [
                        ['section_key' => 'quality_header', 'field_key' => 'badge', 'label' => 'Badge line', 'type' => 'text'],
                        ['section_key' => 'quality_header', 'field_key' => 'heading', 'label' => 'Main heading', 'type' => 'text'],
                        ['section_key' => 'quality_center', 'field_key' => 'video_src', 'label' => 'Center video path', 'type' => 'text'],
                    ],
                ],
                ['legend' => 'Left column — point 1', 'fields' => [
                    ['section_key' => 'quality_l1', 'field_key' => 'title', 'label' => 'Title (optional &lt;font&gt;)', 'type' => 'textarea', 'hint' => 'You may use <font>...</font> for green highlights.'],
                    ['section_key' => 'quality_l1', 'field_key' => 'desc', 'label' => 'Description', 'type' => 'textarea'],
                    ['section_key' => 'quality_l1', 'field_key' => 'image', 'label' => 'Icon image path', 'type' => 'text'],
                ]],
                ['legend' => 'Left — point 2', 'fields' => [
                    ['section_key' => 'quality_l2', 'field_key' => 'title', 'label' => 'Title', 'type' => 'textarea'],
                    ['section_key' => 'quality_l2', 'field_key' => 'desc', 'label' => 'Description', 'type' => 'textarea'],
                    ['section_key' => 'quality_l2', 'field_key' => 'image', 'label' => 'Image path', 'type' => 'text'],
                ]],
                ['legend' => 'Left — point 3', 'fields' => [
                    ['section_key' => 'quality_l3', 'field_key' => 'title', 'label' => 'Title', 'type' => 'textarea'],
                    ['section_key' => 'quality_l3', 'field_key' => 'desc', 'label' => 'Description', 'type' => 'textarea'],
                    ['section_key' => 'quality_l3', 'field_key' => 'image', 'label' => 'Image path', 'type' => 'text'],
                ]],
                ['legend' => 'Right — point 1', 'fields' => [
                    ['section_key' => 'quality_r1', 'field_key' => 'title', 'label' => 'Title', 'type' => 'textarea'],
                    ['section_key' => 'quality_r1', 'field_key' => 'desc', 'label' => 'Description', 'type' => 'textarea'],
                    ['section_key' => 'quality_r1', 'field_key' => 'image', 'label' => 'Image path', 'type' => 'text'],
                ]],
                ['legend' => 'Right — point 2', 'fields' => [
                    ['section_key' => 'quality_r2', 'field_key' => 'title', 'label' => 'Title', 'type' => 'textarea'],
                    ['section_key' => 'quality_r2', 'field_key' => 'desc', 'label' => 'Description', 'type' => 'textarea'],
                    ['section_key' => 'quality_r2', 'field_key' => 'image', 'label' => 'Image path', 'type' => 'text'],
                ]],
                ['legend' => 'Right — point 3', 'fields' => [
                    ['section_key' => 'quality_r3', 'field_key' => 'title', 'label' => 'Title', 'type' => 'textarea'],
                    ['section_key' => 'quality_r3', 'field_key' => 'desc', 'label' => 'Description', 'type' => 'textarea'],
                    ['section_key' => 'quality_r3', 'field_key' => 'image', 'label' => 'Image path', 'type' => 'text'],
                ]],
            ],
        ],
        'sale_banners' => [
            'title' => 'Sale row — small banners',
            'blocks' => [],
            'list_ui' => 'sale_banners',
        ],
        'product_ctg' => [
            'title' => 'Product category / offers block',
            'blocks' => [[
                'legend' => 'Content',
                'fields' => [
                    ['section_key' => 'product_ctg', 'field_key' => 'badge', 'label' => 'Small badge line', 'type' => 'text'],
                    ['section_key' => 'product_ctg', 'field_key' => 'title', 'label' => 'Main title (optional &lt;font&gt;)', 'type' => 'textarea'],
                    ['section_key' => 'product_ctg', 'field_key' => 'desc', 'label' => 'Intro paragraph', 'type' => 'textarea'],
                    ['section_key' => 'product_ctg', 'field_key' => 'p1_title', 'label' => 'Point 1 title', 'type' => 'text'],
                    ['section_key' => 'product_ctg', 'field_key' => 'p1_desc', 'label' => 'Point 1 text', 'type' => 'textarea'],
                    ['section_key' => 'product_ctg', 'field_key' => 'p2_title', 'label' => 'Point 2 title', 'type' => 'text'],
                    ['section_key' => 'product_ctg', 'field_key' => 'p2_desc', 'label' => 'Point 2 text', 'type' => 'textarea'],
                    ['section_key' => 'product_ctg', 'field_key' => 'btn_text', 'label' => 'Button text', 'type' => 'text'],
                    ['section_key' => 'product_ctg', 'field_key' => 'btn_url', 'label' => 'Button URL', 'type' => 'text'],
                ],
            ]],
        ],
        'testimonials' => [
            'title' => 'Testimonials',
            'blocks' => [],
            'list_ui' => 'testimonials',
        ],
        'gallery' => [
            'title' => 'Gallery / blog strip',
            'blocks' => [
                ['legend' => 'Left column', 'fields' => [
                    ['section_key' => 'gallery_left', 'field_key' => 'badge', 'label' => 'Badge', 'type' => 'text'],
                    ['section_key' => 'gallery_left', 'field_key' => 'title', 'label' => 'Heading', 'type' => 'text'],
                    ['section_key' => 'gallery_left', 'field_key' => 'desc', 'label' => 'Description', 'type' => 'textarea'],
                    ['section_key' => 'gallery_left', 'field_key' => 'btn_text', 'label' => 'Button text', 'type' => 'text'],
                    ['section_key' => 'gallery_left', 'field_key' => 'btn_url', 'label' => 'Button URL', 'type' => 'text'],
                ]],
                ['legend' => 'Middle featured card', 'fields' => [
                    ['section_key' => 'gallery_mid', 'field_key' => 'title', 'label' => 'Post title', 'type' => 'text'],
                    ['section_key' => 'gallery_mid', 'field_key' => 'link', 'label' => 'Post link', 'type' => 'text'],
                    ['section_key' => 'gallery_mid', 'field_key' => 'author', 'label' => 'Author line', 'type' => 'text'],
                    ['section_key' => 'gallery_mid', 'field_key' => 'time_label', 'label' => 'Time/meta line', 'type' => 'text'],
                    ['section_key' => 'gallery_mid', 'field_key' => 'desc', 'label' => 'Short description', 'type' => 'textarea'],
                    ['section_key' => 'gallery_mid', 'field_key' => 'image', 'label' => 'Image path', 'type' => 'text'],
                ]],
                ['legend' => 'Right list — item 1', 'fields' => [
                    ['section_key' => 'gallery_side_1', 'field_key' => 'thumb', 'label' => 'Thumbnail', 'type' => 'text'],
                    ['section_key' => 'gallery_side_1', 'field_key' => 'title', 'label' => 'Title', 'type' => 'text'],
                    ['section_key' => 'gallery_side_1', 'field_key' => 'meta1', 'label' => 'Meta (author)', 'type' => 'text'],
                    ['section_key' => 'gallery_side_1', 'field_key' => 'meta2', 'label' => 'Meta (category)', 'type' => 'text'],
                    ['section_key' => 'gallery_side_1', 'field_key' => 'link', 'label' => 'Link', 'type' => 'text'],
                ]],
                ['legend' => 'Right list — item 2', 'fields' => [
                    ['section_key' => 'gallery_side_2', 'field_key' => 'thumb', 'label' => 'Thumbnail', 'type' => 'text'],
                    ['section_key' => 'gallery_side_2', 'field_key' => 'title', 'label' => 'Title', 'type' => 'text'],
                    ['section_key' => 'gallery_side_2', 'field_key' => 'meta1', 'label' => 'Meta 1', 'type' => 'text'],
                    ['section_key' => 'gallery_side_2', 'field_key' => 'meta2', 'label' => 'Meta 2', 'type' => 'text'],
                    ['section_key' => 'gallery_side_2', 'field_key' => 'link', 'label' => 'Link', 'type' => 'text'],
                ]],
                ['legend' => 'Right list — item 3', 'fields' => [
                    ['section_key' => 'gallery_side_3', 'field_key' => 'thumb', 'label' => 'Thumbnail', 'type' => 'text'],
                    ['section_key' => 'gallery_side_3', 'field_key' => 'title', 'label' => 'Title', 'type' => 'text'],
                    ['section_key' => 'gallery_side_3', 'field_key' => 'meta1', 'label' => 'Meta 1', 'type' => 'text'],
                    ['section_key' => 'gallery_side_3', 'field_key' => 'meta2', 'label' => 'Meta 2', 'type' => 'text'],
                    ['section_key' => 'gallery_side_3', 'field_key' => 'link', 'label' => 'Link', 'type' => 'text'],
                ]],
                ['legend' => 'Right list — item 4', 'fields' => [
                    ['section_key' => 'gallery_side_4', 'field_key' => 'thumb', 'label' => 'Thumbnail', 'type' => 'text'],
                    ['section_key' => 'gallery_side_4', 'field_key' => 'title', 'label' => 'Title', 'type' => 'text'],
                    ['section_key' => 'gallery_side_4', 'field_key' => 'meta1', 'label' => 'Meta 1', 'type' => 'text'],
                    ['section_key' => 'gallery_side_4', 'field_key' => 'meta2', 'label' => 'Meta 2', 'type' => 'text'],
                    ['section_key' => 'gallery_side_4', 'field_key' => 'link', 'label' => 'Link', 'type' => 'text'],
                ]],
            ],
        ],
        'services' => [
            'title' => 'Service icons row',
            'blocks' => [],
            'list_ui' => 'services',
        ],
        'instagram' => [
            'title' => 'Instagram strip',
            'blocks' => [],
            'list_ui' => 'instagram',
        ],
    ];
}
