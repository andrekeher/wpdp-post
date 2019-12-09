<?php

namespace AndreKeher\WPDP;

class Post
{
    private $args;
    private $postType;
    private $slug;
    private $name;
    private $singularName;
    private $pluralName;
    private $description;
    private $hierarchical;
    private $taxonomies;
    private $redirectSingleToArchive = false;

    public function __construct($postType, $slug, $name, $singularName, $pluralName, $description = '', $hierarchical = false, $taxonomies = [])
    {
        $this->postType = $postType;
        $this->slug = $slug;
        $this->name = $name;
        $this->singularName = $singularName;
        $this->pluralName = $pluralName;
        $this->description = $description;
        $this->hierarchical = $hierarchical;
        $this->taxonomies = $taxonomies;

        $this->config();
    }

    private function config()
    {
        $labels = [
            'name' => $this->name,
            'singular_name' => $this->singularName,
            'menu_name' => $this->pluralName,
            'name_admin_bar' => $this->singularName,
            'archives' => $this->pluralName,
            'attributes' => 'Atributos',
            'parent_item_colon' => 'Ascendente',
            'all_items' => $this->pluralName,
            'add_new_item' => 'Adicionar',
            'add_new' => 'Adicionar',
            'new_item' => 'Novo',
            'edit_item' => 'Editar',
            'update_item' => 'Alterar',
            'view_item' => 'Visualizar',
            'view_items' => 'Editar',
            'search_items' => 'Buscar',
            'not_found' => 'Nada encontrado =/',
            'not_found_in_trash' => 'Nada encontrado na lixeira =/',
            'featured_image' => 'Imagem destacada',
            'set_featured_image' => 'Configurar imagem destacada',
            'remove_featured_image' => 'Remover imagem destacada',
            'use_featured_image' => 'Usar como imagem destacada',
            'insert_into_item' => 'Inserir item',
            'uploaded_to_this_item' => 'Enviado para este item',
            'items_list' => 'Lista de itens',
            'items_list_navigation' => 'Navegação da lista de itens',
            'filter_items_list' => 'Lista de itens de filtro',
        ];

        $rewrite = [
            'slug' => $this->slug,
            'with_front' => true,
            'pages' => true,
            'feeds' => true,
        ];

        $this->args = [
            'label' => $this->singularName,
            'description' => $this->description,
            'labels' => $labels,
            'supports' => [
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'comments',
            ],
            'taxonomies' => $this->taxonomies,
            'hierarchical' => $this->hierarchical,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'rewrite' => $rewrite,
            'capability_type' => 'post',
            'capabilities' => [],
        ];
    }

    public function init()
    {
        add_action('init', function () {
            register_post_type($this->postType, $this->args);
        }, 0);

        add_action('template_redirect', function () {
            if (! $this->redirectSingleToArchive) {
                return false;
            }
            if (is_singular($this->postType)) {
                wp_redirect(get_post_type_archive_link($this->postType), 301);
                die;
            }
        });

        return $this->postType;
    }

    public function getArgs($key = '')
    {
        if (!empty($key) && isset($this->args[$key])) {
            return $this->args[$key];
        }
        return $this->args;
    }

    public function setArgs($key, $value)
    {
        $this->args[$key] = $value;
    }

    public function setredirectSingleToArchive($value)
    {
        $this->redirectSingleToArchive = $value;
    }
}
