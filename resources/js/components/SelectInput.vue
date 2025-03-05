<template>
    <div v-if="direction === 'vertical'">
        <InputLabel :for="id" :value="label" />
        <div class="grid grid-cols-1">
            <select
                :id="id"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                class="col-start-1 row-start-1 w-full appearance-none border-gray-300 shadow-sm rounded-md bg-white/5 py-1.5 pl-3 pr-8 text-base outline outline-1 -outline-offset-1 outline-white/10 *:bg-gray-800 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                :required="required"
                :disabled="disabled"
            >
                <option v-if="placeholder && !modelValue" value="" disabled>
                    {{ placeholder }}
                </option>
                <option
                    v-for="option in options"
                    :key="option.value"
                    :value="option.value"
                >
                    {{ option.label }}
                </option>
            </select>
            <ChevronDownIcon
                class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-400 sm:size-4"
                aria-hidden="true"
            />
        </div>

        <InputError class="mt-2" :message="error" />
    </div>
    <div v-else>
        <div class="flex items-center">
            <InputLabel :for="id" :value="label" />
            <div class="ml-3 grid grid-cols-1">
                <div>
                    <select
                        :id="id"
                        :value="modelValue"
                        @input="$emit('update:modelValue', $event.target.value)"
                        class="col-start-1 row-start-1 w-full appearance-none border-gray-300 shadow-sm rounded-md bg-white/5 py-1.5 pl-3 pr-8 text-base outline outline-1 -outline-offset-1 outline-white/10 *:bg-gray-800 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                        :required="required"
                        :disabled="disabled"
                    >
                        <option
                            v-if="placeholder && !modelValue"
                            value=""
                            disabled
                        >
                            {{ placeholder }}
                        </option>
                        <option
                            v-for="option in options"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </option>
                    </select>
                </div>
                <InputError class="mt-2" :message="error" />
            </div>
        </div>
    </div>
</template>

<script setup>
import InputLabel from "./InputLabel.vue";
import InputError from "./InputError.vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";

defineProps({
    id: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [String, Number],
        default: "",
    },
    direction: {
        type: String,
        default: "vertical",
    },
    options: {
        type: Array,
        required: true,
        validator: (options) =>
            options.every((opt) => "value" in opt && "label" in opt),
    },
    placeholder: {
        type: String,
        default: "Select an option",
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: "",
    },
});

defineEmits(["update:modelValue"]);
</script>
