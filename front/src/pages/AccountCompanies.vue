<script setup>
    import { ref } from 'vue'
    import axios from 'axios'
    import { biThreeDots } from '@quasar/extras/bootstrap-icons'
    import AccountBase from './AccountBase.vue'

    const rows = ref([])

    axios.post('company/list')
        .then((response) => {
            rows.value = response.data.data.data
            console.log(response.data.data.data);
        })
        .catch((error) => {
            console.log(error);
        })

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
</script>

<template>
    <account-base>
        <div class="box">
            <q-table
                :rows="rows"
                :columns="columns"
                row-key="name"
                hide-bottom
                flat
            >
                <template v-slot:body-cell-name="props">
                    <q-td :props="props">
                        <router-link :to="`/account/company/${props.row.id}`">{{ props.row.name }}</router-link>
                    </q-td>
                </template>

                <template v-slot:body-cell-vacancy="props">
                    <q-td :props="props">
                        <q-badge color="positive" text-color="white" label="20"/>
                    </q-td>
                </template>
                <template v-slot:body-cell-users="props">
                    <q-td :props="props">
                        <q-badge color="negative" text-color="white" label="0"/>
                    </q-td>
                </template>
                <template v-slot:body-cell-feedback="props">
                    <q-td :props="props">
                        <q-badge color="negative" text-color="white" label="0"/>
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
        </div>
    </account-base>
</template>

