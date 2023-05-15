<script setup>
import { onMounted, ref, watch, watchEffect } from 'vue'
import axios from 'axios'
import { biThreeDots } from '@quasar/extras/bootstrap-icons'
import AccountBase from './AccountBase.vue'
import { useElementSize } from '@vueuse/core'

const rows = ref([])
const box = ref(null)
const { width } = useElementSize(box)

axios.post('company/list')
    .then((response) => {
        rows.value = response.data.data.data
        // console.log(response.data.data.data);
    })
    .catch((error) => {
        console.log(error);
    })

const removingTables = (currendWidth) => {
    const { value: width } = currendWidth
    if (width < 300) {
        return visibleColumns.value = ['name']
    }

    if (width < 400) {
        return visibleColumns.value = ['name', 'inn']
    }

    if (width < 500) {
        return visibleColumns.value = ['name', 'inn', 'vacancy']
    }

    if (width < 800) {
        return visibleColumns.value = ['name', 'inn', 'vacancy', 'users']
    }

    if (width < 900) {
        return visibleColumns.value = ['name', 'inn', 'vacancy', 'users', 'feedback']
    }

    if (width < 1000) {
        return visibleColumns.value = ['name', 'inn', 'vacancy', 'users', 'feedback', 'action', 'user']
    }

    if (width < 1300) {
        return visibleColumns.value = ['name', 'inn', 'vacancy', 'users', 'feedback', 'action', 'user', 'createdon']
    }
}

const columns = [
    { name: 'name', label: 'Наименование организации', field: 'name', align: 'left' },
    { name: 'inn', label: 'ИНН', field: 'inn', align: 'left' },
    { name: 'vacancy', label: 'Вакансии', field: 'carbs' },
    { name: 'users', label: 'Пользователи', field: 'users' },
    { name: 'feedback', label: 'Отклики в работе', field: 'feedback' },
    { name: 'action', label: 'Действие', field: 'action' },
    { name: 'user', label: 'Создатель аккаунта', field: 'user', align: 'left' },
    { name: 'createdon', label: 'Дата создания', field: 'createdon', align: 'left' },
]

const unvisibleColumns = ref([])

const visibleColumns = ref(['name', 'inn', 'vacancy', 'users', 'feedback', 'action', 'user', 'createdon'])

watch(width, () => removingTables(width), { deep: true })

// console.log('width =', width.value)
// console.log('visibleColumns =', visibleColumns.value)

onMounted(() => removingTables(width))

</script>

<template>
    <account-base>
        <div class="box" ref="box">
            window size: {{ width }}
            <q-table :rows="rows" :columns="columns" row-key="name" hide-bottom flat :visible-columns="visibleColumns">
                <template v-slot:body-cell-name="props">
                    <q-td :props="props">
                        <router-link :to="`/account/company/${props.row.id}`">{{ props.row.name }}</router-link>
                    </q-td>
                </template>

                <template v-slot:body-cell-vacancy="props">
                    <q-td :props="props" auto-width>
                        <q-badge color="positive" text-color="white" label="20" />
                    </q-td>
                </template>

                <template v-slot:body-cell-users="props">
                    <q-td :props="props">
                        <q-badge color="negative" text-color="white" label="0" />
                    </q-td>
                </template>

                <template v-slot:body-cell-feedback="props">
                    <q-td :props="props">
                        <q-badge color="negative" text-color="white" label="0" />
                    </q-td>
                </template>

                <template v-slot:body-cell-action="props">
                    <q-td :props="props">
                        <q-btn-dropdown color="" flat label="" :dropdown-icon="biThreeDots" class="text-primary">
                            <q-list>
                                <q-item>
                                    <q-item-section>
                                        <q-item-label>Просмотр</q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-item>
                                    <q-item-section>
                                        <q-item-label>Редактирование</q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-item>
                                    <q-item-section>
                                        <q-item-label>Удаление</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </q-btn-dropdown>
                    </q-td>
                </template>
            </q-table>
            <pre> {{ rows }}</pre>
        </div>
    </account-base>
</template>

