<?php

namespace App\Contracts;

/**
 * Interface to enforce CRUD operations on repository
 */
interface Repository
{
    /**
     * Create a new data entry.
     *
     * @param array $params The parameters for the data.
     */
    public function add(array $params);

    /**
     * Get an existing data entry by ID.
     *
     * @param mixed $id The ID for the data entry.
     */
    public function get($id);

    /**
     * Get multiple data entries.
     *
     * @param array $params Parameters to filter data.
     */
    public function getAll(array $params);

    /**
     * Update an existing data entry by ID.
     *
     * @param mixed $id The ID for the data entry.
     * @param array $params The parameters for the data.
     */
    public function edit($id, array $params);

    /**
     * Undocumented function
     *
     * @param mixed $id The ID for the data entry.
     */
    public function delete($id);
}
