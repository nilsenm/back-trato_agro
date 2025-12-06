<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Producto;
use App\Domain\Interfaces\ProductoRepositoryInterface;
use App\Infrastructure\Models\ProductoModel;

class ProductoRepository extends BaseRepository implements ProductoRepositoryInterface
{
    public function __construct(ProductoModel $model)
    {
        parent::__construct($model);
    }

    public function findByNombre(string $nombre): ?Producto
    {
        $model = $this->model->where('nombre', $nombre)->first();
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function findBySubcategoria(int $idSubcategoria): array
    {
        $models = $this->model->where('id_subcategoria', $idSubcategoria)->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    /**
     * Obtiene productos filtrados por subcategoría y usuario
     */
    public function findBySubcategoriaAndUsuario(int $idSubcategoria, int $idUsuario): array
    {
        $models = $this->model->where('id_subcategoria', $idSubcategoria)
            ->where('id_usuario', $idUsuario)
            ->get();
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    public function find($id): ?Producto
    {
        $model = parent::find($id);
        
        if (!$model) {
            return null;
        }

        return $this->toEntity($model);
    }

    public function all(array $columns = ['*']): array
    {
        if ($columns === ['*']) {
            $models = $this->model->all();
        } else {
            $models = $this->model->get($columns);
        }
        
        return $models->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();
    }

    /**
     * Obtiene productos con paginación y filtros opcionales
     */
    public function listado(int $page = 1, int $perPage = 10, ?string $nombre = null, ?int $idSubcategoria = null): array
    {
        $query = $this->model->query();

        // Aplicar filtros
        if ($nombre) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        }

        if ($idSubcategoria) {
            $query->where('id_subcategoria', $idSubcategoria);
        }

        // Paginar
        $paginated = $query->paginate($perPage, ['*'], 'page', $page);

        // Convertir a entidades
        $productos = $paginated->getCollection()->map(function ($model) {
            return $this->toEntity($model);
        })->toArray();

        return [
            'data' => $productos,
            'current_page' => $paginated->currentPage(),
            'per_page' => $paginated->perPage(),
            'total' => $paginated->total(),
            'last_page' => $paginated->lastPage(),
            'from' => $paginated->firstItem(),
            'to' => $paginated->lastItem(),
        ];
    }

    public function create(array $data): Producto
    {
        $model = parent::create($data);
        return $this->toEntity($model);
    }

    public function update($id, array $data): bool
    {
        $model = $this->model->find($id);
        
        if (!$model) {
            return false;
        }

        return $model->update($data);
    }

    private function toEntity(ProductoModel $model): Producto
    {
        $entity = new Producto(
            $model->nombre,
            $model->imagen ?? '-',
            $model->id_subcategoria,
            $model->descripcion,
            $model->id_usuario,
            $model->estado ?? 'ACTIVO'
        );
        
        if ($model->id_producto) {
            $entity->setId($model->id_producto);
        }

        return $entity;
    }
}


