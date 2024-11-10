<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;

class Repository extends RepositoryAbstract
{

    protected Model $model;

    /**
     * get all data
     *
     * @return Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * get all data order by created at desc
     *
     * @return Collection
     */
    public function getLatest()
    {
        return $this->model->latest()->get();
    }

    public function getApprovedLatest()
    {
        return $this->model->latest()->includeNotApprove()->get();
    }

    /**
     * get all data order by created at desc
     *
     * @param string $column
     * @param string $method
     * @return Collection
     */
    public function getOrderBy(string $column, string $method = 'asc')
    {
        return $this->model->orderBy($column, $method)->get();
    }

    /**
     * store data to db
     *
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        return $this->model->insert($data);
    }

    /**
     * store data to db
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * find or store data to db
     *
     * @param array $data
     * @param array $data2
     * @return Model
     */
    public function firstOrCreate(array $data, array $data2 = [])
    {
        return $this->model->firstOrCreate($data, $data2);
    }

    /**
     * store data to db
     *
     * @param array $data
     * @return Model
     */
    public function store(array $data)
    {
        return $this->create($data);
    }

    /**
     * find data by id
     *
     * @param mixed $id
     * @param array $columns
     * @return Model
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->model->query()
            ->where('id', $id)
            ->select($columns)
            ->first();
    }

    /**
     * find or fail data by id
     *
     * @param mixed $id
     * @return Model
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * find with data by id
     *
     * @param mixed $id
     * @param array $with
     * @return Model
     */
    public function findWith($id, $with = [])
    {
        return $this->model->where('id', $id)->with($with)->first();
    }

    /**
     * find with or fail data by id
     *
     * @param mixed $id
     * @param array $with
     * @return Model
     */
    public function findWithOrFail($id, $with = [])
    {
        return $this->model->where('id', $id)->with($with)->firstOrFail();
    }

    /**
     * update data by id
     *
     * @param array $data
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function update(array $data, int $id, array $columns = ['*'])
    {
        $model = $this->find($id);
        if ($model) {
            $model->update($data);
            return $this->find($id, $columns);
        }
        return 0;
    }

    /**
     * update data by key
     *
     * @param array $data
     * @param string $key
     * @return Model
     */
    public function updateByKey(array $data, string $key)
    {
        $model = $this->model->where('key', $key);
        if ($model) {
            $model->update($data);
            return $model;
        }
        return 0;
    }

    /**
     * delete data by id
     *
     * @param int $id
     * @return Model
     */
    public function delete(int $id)
    {
        $model = $this->find($id);
        if ($model) {
            return $model->delete();
        }
        return 0;
    }

    /**
     * delete data by id
     *
     * @param int $id
     * @return Model
     */
    public function destroy(int $id)
    {
        return $this->delete($id);
    }

    /**
     * get data as pagination
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginate()
    {
        $perPage = request('perPage', 20);
        return $this->model->query()
            ->when(request('sort') === 'oldest', function ($query) {
                $query->sortBy('id', 'asc');
            })
            ->when(request('sort') === 'latest' || request('sort') === null, function ($query) {
                $query->latest();
            })
            ->paginate($perPage);
    }

    /**
     * getFilter
     *
     * @return Collection
     */
    public function getFilter()
    {
        return $this->model
            ->get();
    }

    /**
     * getWhereIn
     *
     * @param string $column
     * @param array $data
     * @param array $columns
     * @return Collection
     */
    public function getWhereIn(string $column, array $data, array $columns = ['*'])
    {
        return $this->model->query()
            ->select($columns)
            ->whereIn($column, $data)
            ->get();
    }

    /**
     * get query
     *
     * @return Model
     */
    public function query()
    {
        return $this->model->query();
    }

    /**
     * get data as datatable
     *
     * @return Response
     */
    protected function generateDataTables($query, array $params)
    {
        $dataTables = DataTables::of($query)->addIndexColumn();
        foreach ($params['editColumns'] as $column => $value) {
            $dataTables->editColumn($column, $value);
        }
        return $dataTables->rawColumns($params['rawColumns'] ?? [])->make(true);
    }

    /**
     * get data as datatable
     *
     * @return Response
     */
    public function getYajraDataTables()
    {
        return $this->generateDataTables($this->query(),);
        return DataTables::of($this->query())->addIndexColumn()->rawColumns([])->make(true);
    }

    /**
     * get data as select options
     *
     * @return array
     */
    public function getSelectOptions($key, $value)
    {
        return $this->query()->select($key, $value)->get()->pluck($key, $value)->toArray();
    }

    /**
     * get yajra columns
     *
     * @return string
     */
    public function getYajraColumns()
    {
        return json_encode([
            [
                'data'       => 'DT_RowIndex',
                'name'       => 'DT_RowIndex',
                'searchable' => false,
                'orderable'  => false
            ],
            [
                'data' => 'action',
                'name' => 'action',
                'orderable' => false,
                'searchable' => false
            ],
        ]);
    }
}
