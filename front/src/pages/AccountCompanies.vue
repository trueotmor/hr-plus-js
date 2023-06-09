<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'
import { biThreeDots, biEyeFill, biPencilFill, biX, biDashCircle, biPlusCircle } from '@quasar/extras/bootstrap-icons'
import AccountBase from './AccountBase.vue'
import { useElementSize } from '@vueuse/core'
import { tableColumns, tableColumnsNames } from '../constants'

const rows = ref([])
const box = ref(null)
const { width } = useElementSize(box)

axios.post('company/list')
    .then((response) => {
        rows.value = response.data.data.data
        console.log(response.data.data.data)
    })
    .catch((error) => {
        console.log(error);
    })

const visibleColumns = computed(() => {
    const visible = []
    for (const col in tableColumns) {
        if ((tableColumns[col].hideFrom || 0) < width.value) {
            visible.push(tableColumns[col].name)
        }
    }
    return visible
})

</script>

<template>
    <account-base>
        <div class="box" ref="box">
            <q-table :rows="rows" :columns="tableColumns" row-key="name" hide-bottom flat :visible-columns="visibleColumns"
                binary-state-sort class="companies__table">

                <!-- <template v-slot:header="props">
                    <q-td :props="props">
                        <router-link :to="`/account/company/${props.row.id}`">{{ props.row.name }}</router-link>
                    </q-td>
                </template> -->

                <!-- шапка -->
                <template v-slot:header="props">
                    <q-tr :props="props">
                        <q-th auto-width />
                        <q-th v-for="col in props.cols" :key="col.name" :props="props">
                            {{ col.label }}
                        </q-th>
                    </q-tr>
                </template>

                <template v-slot:body="props">
                    <q-tr :props="props">
                        <!-- кнопка + -->
                        <q-td auto-width>
                            <q-btn v-if="tableColumnsNames.length != visibleColumns.length && !props.expand"
                                @click="props.expand = !props.expand" round flat class="companies__table-button">
                                <q-icon :name="biPlusCircle" color="green-12"
                                    class=" companies__table-icon companies__table-icon--plus" />
                            </q-btn>
                            <q-btn v-if="tableColumnsNames.length != visibleColumns.length && props.expand"
                                @click="props.expand = !props.expand" round flat class="companies__table-button">
                                <q-icon :name="biDashCircle" color="red-5"
                                    class="companies__table-icon companies__table-icon--minus" />
                            </q-btn>
                        </q-td>
                        <q-td v-for=" col  in  props.cols " :key="col.name" :props="props">
                            <q-btn-dropdown flat :dropdown-icon="biThreeDots" class="text-primary" size="10px"
                                padding="10px" no-icon-animation v-if="col.name === 'action'">
                                <q-list class="companies__menu-list">
                                    <q-item clickable v-close-popup @click=" ">
                                        <q-item-section>
                                            <q-icon :name="biEyeFill" color="light-blue-5"
                                                class="companies__action-icon companies__action-icon--blue" />
                                            <q-item-label>Просмотр</q-item-label>
                                        </q-item-section>
                                    </q-item>
                                    <q-item clickable v-close-popup @click=" ">
                                        <q-item-section>
                                            <q-icon :name="biPencilFill" color="green-12"
                                                class="companies__action-icon companies__action-icon--green" />
                                            <q-item-label>Редактирование</q-item-label>
                                        </q-item-section>
                                    </q-item>
                                    <q-item clickable v-close-popup @click=" ">
                                        <q-item-section>
                                            <q-icon :name="biX" color="red-5"
                                                class="companies__action-icon companies__action-icon--red" />
                                            <q-item-label>Удаление</q-item-label>
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-btn-dropdown>
                        </q-td>
                    </q-tr>
                    <!-- скрытый контент -->
                    <q-tr v-show="props.expand" :props="props">
                        <q-td colspan="100%">
                            <div class="text-left">
                                <ul>
                                    <template v-for=" i, k  in  props.row ">
                                        <!-- если колонка есть, но скрыта -->
                                        <li v-if="tableColumnsNames.includes(k) && !visibleColumns.includes(k)">
                                            {{ k }} {{ i }}
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </q-td>
                    </q-tr>
                </template>
                <!-- <template v-slot:body-cell-name="props">
                    <q-td :props="props">
                        <router-link :to="`/account/company/${props.row.id}`">{{ props.row.name }}</router-link>
                    </q-td>
                </template> -->
            </q-table>
        </div>
    </account-base>
</template>

<style lang="scss">
.q-menu:has(.companies__menu-list) {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 0.2em;
    padding: 0.5rem 0;

    & .q-item {
        padding: 0.6em 1.2em;

        &__section {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: start;
            font-size: small;

            & .q-icon {
                margin-right: 5px;
                margin-top: -3px;
            }
        }
    }
}

.companies__table {
    & thead {
        background: #F6F6F7;

        & th {
            font-size: 1em;
            font-weight: 700;
            color: #4B4B5A;
            padding: 16px 30px;
        }

    }


    & th.sortable {
        direction: rtl;
        padding-left: 0;
    }

    &-button {
        font-size: 10px;

        & svg {
            stroke: 2px;
        }

        & .q-focus-helper {
            display: none;
        }
    }
}

.companies__action-icon {
    &--red svg {
        transform: scale(1.5);
    }
}
</style>