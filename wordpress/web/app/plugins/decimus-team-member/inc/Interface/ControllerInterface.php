<?php

namespace Gulacsi\TeamMember\Interface;

defined('ABSPATH') or die();

interface ControllerInterface
{
    /**
     * Form action switcher
     */
    public function form_action(): void;

    /**
     * Entity list
     * @return void
     */
    public function list_table(): void;

    /**
     * Add new entity
     * @return void
     */
    public function add_form(): void;

    /**
     * Insert new entity
     * @return void
     */
    public function handle_insert(): void;

    /**
     * Update entity
     * @return void
     */
    public function handle_update(): void;

    /**
     * Delete entity
     * @return void
     */
    public function handle_delete(): void;

}
