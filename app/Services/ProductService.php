<?php
namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Modules\Ynotz\EasyAdmin\Services\FormHelper;
use Modules\Ynotz\EasyAdmin\Services\IndexTable;
use Modules\Ynotz\EasyAdmin\Traits\IsModelViewConnector;
use Modules\Ynotz\EasyAdmin\Contracts\ModelViewConnector;
use Modules\Ynotz\EasyAdmin\RenderDataFormats\CreatePageData;
use Modules\Ynotz\EasyAdmin\RenderDataFormats\EditPageData;
use Modules\Ynotz\EasyAdmin\Services\ColumnLayout;
use Modules\Ynotz\EasyAdmin\Services\Form;
use Modules\Ynotz\EasyAdmin\Services\RowLayout;

class ProductService implements ModelViewConnector {
    use IsModelViewConnector;
    private $indexTable;

    private function getFileFields(): array
    {
        return [
            'image'
        ];
    }

    public function __construct()
    {
        $this->modelClass = Product::class;
        $this->indexTable = new IndexTable();
        $this->selectionEnabled = true;

        // $this->idKey = 'id';
        // $this->selects = '*';
        // $this->selIdsKey = 'id';
        // $this->searchesMap = [];
        // $this->sortsMap = [];
        // $this->filtersMap = [
        //     'author' => 'user_id' // Example
        // ];
        // $this->orderBy = ['created_at', 'desc'];
        // $this->sqlOnlyFullGroupBy = true;
        // $this->defaultSearchColumn = 'name';
        // $this->defaultSearchMode = 'startswith';
        // $this->relations = [];
        // $this->selectionEnabled = false;
        // $this->downloadFileName = 'results';
    }

    protected function relations()
    {
        return [
            'tags' => [
                'search_column' => 'id',
                'filter_column' => 'id',
                'sort_column' => 'id',
            ],
        ];
        // // Example:
        // return [
            // 'author' => [
            //     'search_column' => 'id',
            //     'filter_column' => 'id',
            //     'sort_column' => 'id',
            // ],
        //     'reviewScore' => [
        //         'search_column' => 'score',
        //         'filter_column' => 'id',
        //         'sort_column' => 'id',
        //     ],
        // ];
    }
    protected function getPageTitle(): string
    {
        return "Products List";
    }

    protected function getIndexHeaders(): array
    {
        return $this->indexTable->addHeaderColumn(
            title: 'Name',
            sort: ['key' => 'name']
        )->addHeaderColumn(
            title: 'Price',
            sort: ['key' => 'price']
        )->addHeaderColumn(
            title: 'Action'
        )->getHeaderRow();
        // // Example:
        // return $this->indexTable->addHeaderColumn(
        //     title: 'Title',
        //     sort: ['key' => 'title'],
        // )->addHeaderColumn(
        //     title: 'Author',
        //     filter: ['key' => 'author', 'options' => User::all()->pluck('name', 'id')]
        // )->addHeaderColumn(
        //     title: 'Review Score',
        // )->addHeaderColumn(
        //     title: 'Actions'
        // )->getHeaderRow();
    }

    protected function getIndexColumns(): array
    {
        return $this->indexTable->addColumn(
            fields: ['name'],
        )->addColumn(
            fields: ['price']
        )->addActionColumn(
            editRoute: 'products.edit',
            deleteRoute: 'products.destroy'
        )
        ->getRow();
        // // Example:
        // return $this->indexTable->addColumn(
        //     fields: ['title'],
        // )->addColumn(
        //     fields: ['name'],
        //     relation: 'author',
        // )->addColumn(
        //     fields: ['score'],
        //     relation: 'reviewScore',
        // )
        // ->addActionColumn(
        //     editRoute: $this->getEditRoute(),
        //     deleteRoute: $this->getDestroyRoute(),
        // )->getRow();
    }

    public function getAdvanceSearchFields(): array
    {
        return [];
        // // Example:
        // return $this->indexTable->addSearchField(
        //     key: 'title',
        //     displayText: 'Title',
        //     valueType: 'string',
        // )
        // ->addSearchField(
        //     key: 'author',
        //     displayText: 'Author',
        //     valueType: 'list_string',
        //     options: User::all()->pluck('name', 'id')->toArray(),
        //     optionsType: 'key_value'
        // )
        // ->addSearchField(
        //     key: 'reviewScore',
        //     displayText: 'Review Score',
        //     valueType: 'numeric',
        // )
        // ->getAdvSearchFields();
    }

    public function getDownloadCols(): array
    {
        return [];
        // // Example
        // return [
        //     'title',
        //     'author.name'
        // ];
    }

    public function getDownloadColTitles(): array
    {
        return [
            'title' => 'Title',
            'author.name' => 'Author'
        ];
    }

    public function getCreatePageData(): CreatePageData
    {
        return new CreatePageData(
            title: 'Create Product',
            form: FormHelper::makeForm(
                title: 'Create Product',
                id: 'form_products_create',
                action_route: 'products.store',
                success_redirect_route: 'products.index',
                items: $this->getCreateFormElements(),
                layout: $this->buildCreateFormLayout(),
                label_position: 'top'
            )
        );
    }

    public function getEditPageData($id): EditPageData
    {
        return new EditPageData(
            title: 'Edit Product',
            form: FormHelper::makeEditForm(
                title: 'Edit Product',
                id: 'form_products_create',
                action_route: 'products.update',
                action_route_params: ['id' => $id],
                success_redirect_route: 'products.index',
                items: $this->getEditFormElements(),
                label_position: 'float'
            ),
            instance: $this->getQuery()->where('id', $id)->get()->first()
        );
    }

    /*
    public function getShowPageData($id): ShowPageData
    {
        return new ShowPageData(
            Str::ucfirst($this->getModelShortName()),
            $this->getQuery()->where($this->key, $id)->get()->first()
        );
    }
    */

    private function formElements(): array
    {
        return [
            'name' => FormHelper::makeInput(
                inputType: 'text',
                key: 'name',
                label: 'Name'
            ),
            'category' => FormHelper::makeSelect(
                key: 'category',
                label: 'Category',
                options: Category::all()
            ),
            'tags' => FormHelper::makeSelect(
                key: 'tags',
                label: 'Tags',
                options: Tag::all(),
                properties: ['multiple' => true, 'required' => true]
            ),
            'image' => FormHelper::makeImageUploader(
                key: 'image',
                label: 'Cover Image'
            ),
            'description' => FormHelper::makeTextarea(
                key: 'description',
                label: 'Description'
            ),
            'price' => FormHelper::makeInput(
                inputType: 'text',
                key: 'price',
                label: 'Price'
            ),
            'features' => FormHelper::makeDynamicInput(
                key: 'features',
                label: 'Features',
                component: 'inputs.features'
            )
        ];
        // // Example:
        // return [
        //     'title' => FormHelper::makeInput(
        //         inputType: 'text',
        //         key: 'title',
        //         label: 'Title',
        //         properties: ['required' => true],
        //     ),
        //     'description' => FormHelper::makeTextarea(
        //         key: 'description',
        //         label: 'Description'
        //     ),
        // ];
    }

    private function getQuery()
    {
        return $this->modelClass::query();
        // // Example:
        // return $this->modelClass::query()->with([
        //     'author' => function ($query) {
        //         $query->select('id', 'name');
        //     }
        // ]);
    }

    public function getStoreValidationRules(): array
    {
        return [
            'name' => ['required', 'string'],
            'category' => ['required', 'integer'],
            'tags' => ['required', 'array'],
            'image' => ['required', 'string'],
            'description' => ['sometimes', 'string'],
            'price' => ['required', 'numeric'],
            'features' => ['required', 'array'],
        ];
        // // Example:
        // return [
        //     'title' => ['required', 'string'],
        //     'description' => ['required', 'string'],
        // ];
    }

    public function getUpdateValidationRules($id): array
    {
        return [
            'name' => ['required', 'string'],
            'category' => ['required', 'integer'],
            'tags' => ['required', 'array'],
            'image' => ['required', 'string'],
            'description' => ['sometimes', 'string'],
            'price' => ['required', 'numeric'],
            'features' => ['required', 'array'],
        ];
        // // Example:
        // $arr = $this->getStoreValidationRules();
        // return $arr;
    }

    public function processBeforeStore(array $data): array
    {
        // // Example:
        // $data['user_id'] = auth()->user()->id;
        $data['categoryId'] = $data['category'];
        unset($data['category']);
        return $data;
    }

    public function processBeforeUpdate(array $data): array
    {
        // // Example:
        // $data['user_id'] = auth()->user()->id;

        $data['categoryId'] = $data['category'];
        unset($data['category']);
        return $data;
    }

    public function processAfterStore($instance): void
    {
        //Do something with the created $instance
    }

    public function processAfterUpdate($oldInstance, $instance): void
    {
        //Do something with the updated $instance
    }

    public function buildCreateFormLayout(): array
    {
        return (
            (new ColumnLayout())->addElements([
                (new RowLayout())->addElements(
                    [
                        (new ColumnLayout())->addInputSlot('name'),
                        (new ColumnLayout())->addInputSlot('price')
                    ]
                ),
                (new RowLayout())->addElements([
                    (new ColumnLayout())->addInputSlot('category'),
                    (new ColumnLayout())->addInputSlot('tags')
                ]),
                (new RowLayout())->addInputSlot('image'),
                (new RowLayout())->addInputSlot('description'),
                (new RowLayout())->addInputSlot('features'),
            ])
        )->getLayout();
        // // Example
        //  $layout = (new ColumnLayout())
        //     ->addElements([
        //             (new RowLayout())
        //                 ->addElements([
        //                     (new ColumnLayout(width: '1/2'))->addInputSlot('title'),
        //                     (new ColumnLayout(width: '1/2'))->addInputSlot('description'),
        //                 ])
        //         ]
        //     );
        // return $layout->getLayout();
    }
}

?>
