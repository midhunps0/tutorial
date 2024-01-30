@props([
    'element',
    '_old' => [],
    '_current_values' => [],
    'xerrors' => [],
    'label_position' => 'top',
])
@php
    $name = $element['key'];
    if (str_contains($name, '.')) {
        $relArr = explode('.', $name);
        $name = $relArr[0];
        for ($i=1; $i < count($relArr); $i++) {
            $name = $name . '[' . $relArr[$i] . ']';
        }
        $relName = $relArr[0];
        unset($relArr[0]);
        $relFields = $relArr;
        $theVal = $_old[$relName] ?? null;
        if (isset($theVal)) {
            foreach ($relFields as $field) {
                $theVal = $theVal->$field;
            }
        }
    } else {
        $theVal = $_old[$name] ?? '';
    }
    $type = $element['input_type'];
    $label = $element['label'];
    $authorised = $element['authorised'] ?? true;
    $width = $element['width'] ?? 'full';
    $placeholder = $element["placeholder"] ?? null;
    $wrapper_styles = $element["wrapper_styles"] ?? null;
    $input_styles = $element["input_styles"] ?? null;
    $properties = $element['properties'] ?? [];
    $fire_input_event = $element['fire_input_event'] ?? false;
    $update_on_events = $element['update_on_events'] ?? null;
    $reset_on_events = $element['reset_on_events'] ?? null;
    $toggle_on_events = $element['toggle_on_events'] ?? null;
    $show = $element['show'] ?? true;

    $wclass = 'w-full';
    switch ($width) {
        case 'full':
            $wclass = 'w-full';
            break;
        case '1/2':
            $wclass = 'w-1/2';
            break;
        case '1/3':
            $wclass = 'w-1/3';
            break;
        case '2/3':
            $wclass = 'w-2/3';
            break;
        case '1/4':
            $wclass = 'w-1/4';
            break;
        case '3/4':
            $wclass = 'w-3/4';
            break;
    }
@endphp
@if ($authorised)
    <div x-data="{
            features: [
                {
                    name: '',
                    value: '',
                    unit: ''
                }
            ],
            addRow() {
                this.features.push(
                    {
                        name: '',
                        value: '',
                        unit: ''
                    }
                );
            },
            deleteRow(index) {
                this.features = this.features.filter((f, iteration) => {
                    return index != iteration;
                });
            }
        }"
        x-init="
            @if(isset($_old[$name]))
                features = {{Js::from($_old[$name])}} ?? [
                    {
                        name: '',
                        value: '',
                        unit: ''
                    }
                ];
            @endif
        "
        >
        <label class="form-control w-full">
            <div class="label">
              <span class="label-text">Features</span>
            </div>
        </label>
        <div>
            <div class="flex flex-row space-x-2">
                <div class="w-1/4">Name</div>
                <div class="w-1/4">Value</div>
                <div class="w-1/4">Unit</div>
                <div></div>
            </div>
            <template x-for="(f, i) in features">
                <div class="flex flex-row space-x-2">
                    <input :name="'features['+i+'][name]'" class="input input-sm w-1/4" x-model="f.name" type="text">
                    <input :name="'features['+i+'][value]'" class="input input-sm w-1/4" x-model="f.value" type="text">
                    <input :name="'features['+i+'][unit]'" class="input input-sm w-1/4" x-model="f.unit" type="text">
                    <div class="my-2">
                        <button x-show="i == features.length - 1 " @click.prevent.stop="addRow()" class="btn btn-xs btn-success" type="button">
                            <x-easyadmin::display.icon icon="easyadmin::icons.plus"/>
                        </button>
                        <button x-show="features.length > 1" @click.prevent.stop="deleteRow(i)" class="btn btn-xs btn-error" type="button">
                            <x-easyadmin::display.icon icon="easyadmin::icons.delete"/>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
@endif
