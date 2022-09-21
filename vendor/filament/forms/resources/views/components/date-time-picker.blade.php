<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div
        x-data="dateTimePickerFormComponent({
            displayFormat: '{{ convert_date_format($getDisplayFormat())->to('day.js') }}',
            firstDayOfWeek: {{ $getFirstDayOfWeek() }},
            isAutofocused: @js($isAutofocused()),
            isDisabled: @js($isDisabled()),
            locale: @js(app()->getLocale()),
            shouldCloseOnDateSelection: @js($shouldCloseOnDateSelection()),
            state: $wire.{{ $applyStateBindingModifiers('entangle(\'' . $getStatePath() . '\')') }},
        })"
        {{ $attributes->merge($getExtraAttributes())->class(['filament-forms-date-time-picker-component relative']) }}
        {{ $getExtraAlpineAttributeBag() }}
    >
        <input x-ref="maxDate" type="hidden" value="{{ $getMaxDate() }}" />
        <input x-ref="minDate" type="hidden" value="{{ $getMinDate() }}" />

        <button
            @unless($isDisabled())
                x-ref="button"
                x-on:click="togglePanelVisibility()"
                x-on:keydown.enter.stop.prevent="isOpen() ? selectDate() : togglePanelVisibility()"
                x-on:keydown.arrow-left.stop.prevent="focusPreviousDay()"
                x-on:keydown.arrow-right.stop.prevent="focusNextDay()"
                x-on:keydown.arrow-up.stop.prevent="focusPreviousWeek()"
                x-on:keydown.arrow-down.stop.prevent="focusNextWeek()"
                x-on:keydown.backspace.stop.prevent="clearState()"
                x-on:keydown.clear.stop.prevent="clearState()"
                x-on:keydown.delete.stop.prevent="clearState()"
                aria-label="{{ $getPlaceholder() }}"
                dusk="filament.forms.{{ $getStatePath() }}.open"
            @endunless
            type="button"
            {{ $getExtraTriggerAttributeBag()->class([
                'relative w-full cursor-default rounded-lg border-none bg-white py-2 pl-3 pr-10 text-left shadow-sm ring-1 ring-inset transition duration-75 rtl:pl-10 rtl:pr-3 sm:py-2.5 sm:text-sm',
                'focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-500' => ! $isDisabled(),
                'dark:bg-gray-700' => config('forms.dark_mode'),
                'ring-gray-300' => ! $errors->has($getStatePath()),
                'dark:ring-gray-600' => (! $errors->has($getStatePath())) && config('forms.dark_mode'),
                'ring-danger-500' => $errors->has($getStatePath()),
                'opacity-70' => $isDisabled(),
                'dark:text-gray-300' => $isDisabled() && config('forms.dark_mode'),
            ]) }}
        >
            <input
                readonly
                placeholder="{{ $getPlaceholder() }}"
                x-model="displayText"
                {!! ($id = $getId()) ? "id=\"{$id}\"" : null !!}
                @class([
                    'h-full w-full border-0 bg-transparent p-0 placeholder-gray-400 focus:placeholder-gray-500 focus:outline-none focus:ring-0 sm:text-sm',
                    'dark:bg-gray-700 dark:placeholder-gray-400' => config('forms.dark_mode'),
                    'cursor-default' => $isDisabled(),
                ])
            />

            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none rtl:right-auto rtl:left-0 rtl:pl-2">
                <svg @class([
                    'w-5 h-5 text-gray-400',
                    'dark:text-gray-400' => config('forms.dark_mode'),
                ]) xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </span>
        </button>

        @unless ($isDisabled())
            <div
                x-ref="panel"
                x-cloak
                x-float.placement.bottom-start.offset.flip.shift="{ offset: 8 }"
                wire:ignore.self
                wire:key="{{ $this->id }}.{{ $getStatePath() }}.{{ $field::class }}.panel"
                @class([
                    'absolute hidden z-10 my-1 bg-white border border-gray-300 rounded-lg shadow-md',
                    'dark:bg-gray-700 dark:border-gray-600' => config('forms.dark_mode'),
                    'p-4 min-w-[16rem] w-fit' => $hasDate(),
                ])
            >
                <div class="space-y-3">
                    @if ($hasDate())
                        <div class="flex items-center justify-between space-x-1 rtl:space-x-reverse">
                            <select
                                x-model="focusedMonth"
                                @class([
                                    'grow px-1 py-0 text-lg font-medium text-gray-800 border-0 cursor-pointer focus:ring-0 focus:outline-none',
                                    'dark:bg-gray-700 dark:text-gray-200' => config('forms.dark_mode'),
                                ])
                                dusk="filament.forms.{{ $getStatePath() }}.focusedMonth"
                            >
                                <template x-for="(month, index) in months">
                                    <option x-bind:value="index" x-text="month"></option>
                                </template>
                            </select>

                            <input
                                type="number"
                                inputmode="numeric"
                                x-model.debounce="focusedYear"
                                @class([
                                    'w-20 p-0 text-lg text-right border-0 focus:ring-0 focus:outline-none',
                                    'dark:bg-gray-700 dark:text-gray-200' => config('forms.dark_mode'),
                                ])
                                dusk="filament.forms.{{ $getStatePath() }}.focusedYear"
                            />
                        </div>

                        <div class="grid grid-cols-7 gap-1">
                            <template x-for="(day, index) in dayLabels" :key="index">
                                <div
                                    x-text="day"
                                    @class([
                                        'text-xs font-medium text-center text-gray-800',
                                        'dark:text-gray-200' => config('forms.dark_mode'),
                                    ])
                                ></div>
                            </template>
                        </div>

                        <div role="grid" class="grid grid-cols-7 gap-1">
                            <template x-for="day in emptyDaysInFocusedMonth" x-bind:key="day">
                                <div class="text-sm text-center border border-transparent"></div>
                            </template>

                            <template x-for="day in daysInFocusedMonth" x-bind:key="day">
                                <div
                                    x-text="day"
                                    x-on:click="dayIsDisabled(day) || selectDate(day)"
                                    x-on:mouseenter="setFocusedDay(day)"
                                    role="option"
                                    x-bind:aria-selected="focusedDate.date() === day"
                                    x-bind:class="{
                                        'text-gray-700 @if (config('forms.dark_mode')) dark:text-gray-300 @endif': ! dayIsSelected(day),
                                        'cursor-pointer': ! dayIsDisabled(day),
                                        'bg-primary-50 @if (config('forms.dark_mode')) dark:bg-primary-100 dark:text-gray-600 @endif': dayIsToday(day) && ! dayIsSelected(day) && focusedDate.date() !== day && ! dayIsDisabled(day),
                                        'bg-primary-200 @if (config('forms.dark_mode')) dark:text-gray-600 @endif': focusedDate.date() === day && ! dayIsSelected(day),
                                        'bg-primary-500 text-white': dayIsSelected(day),
                                        'cursor-not-allowed pointer-events-none': dayIsDisabled(day),
                                        'opacity-50': focusedDate.date() !== day && dayIsDisabled(day),
                                    }"
                                    x-bind:dusk="'filament.forms.{{ $getStatePath() }}' + '.focusedDate.' + day"
                                    class="text-sm leading-loose text-center transition duration-100 ease-in-out rounded-full"
                                ></div>
                            </template>
                        </div>
                    @endif

                    @if ($hasTime())
                        <div
                            @class([
                                'flex items-center justify-center bg-gray-50 py-2 rounded-lg rtl:flex-row-reverse',
                                'dark:bg-gray-800' => config('forms.dark_mode'),
                            ])
                        >
                            <input
                                max="23"
                                min="0"
                                type="number"
                                inputmode="numeric"
                                x-model.debounce="hour"
                                @class([
                                    'w-16 p-0 pr-1 text-xl bg-gray-50 text-center text-gray-700 border-0 focus:ring-0 focus:outline-none',
                                    'dark:text-gray-200 dark:bg-gray-800' => config('forms.dark_mode'),
                                ])
                                dusk="filament.forms.{{ $getStatePath() }}.hour"
                            />

                            <span
                                @class([
                                    'text-xl font-medium bg-gray-50 text-gray-700',
                                    'dark:text-gray-200 dark:bg-gray-800' => config('forms.dark_mode'),
                                ])
                            >:</span>

                            <input
                                max="59"
                                min="0"
                                type="number"
                                inputmode="numeric"
                                x-model.debounce="minute"
                                @class([
                                    'w-16 p-0 pr-1 text-xl text-center bg-gray-50 text-gray-700 border-0 focus:ring-0 focus:outline-none',
                                    'dark:text-gray-200 dark:bg-gray-800' => config('forms.dark_mode'),
                                ])
                                dusk="filament.forms.{{ $getStatePath() }}.minute"
                            />

                            @if ($hasSeconds())
                                <span
                                    @class([
                                        'text-xl font-medium text-gray-700 bg-gray-50',
                                        'dark:text-gray-200 dark:bg-gray-800' => config('forms.dark_mode'),
                                    ])
                                >:</span>


                                <input
                                    max="59"
                                    min="0"
                                    type="number"
                                    inputmode="numeric"
                                    x-model.debounce="second"
                                    dusk="filament.forms.{{ $getStatePath() }}.second"
                                    @class([
                                        'w-16 p-0 pr-1 text-xl text-center bg-gray-50 text-gray-700 border-0 focus:ring-0 focus:outline-none',
                                        'dark:text-gray-200 dark:bg-gray-800' => config('forms.dark_mode'),
                                    ])
                                />
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endunless
    </div>
</x-dynamic-component>
