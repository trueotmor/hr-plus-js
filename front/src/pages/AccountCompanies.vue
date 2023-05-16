<script setup>
import { onMounted, ref, watch } from 'vue'
import axios from 'axios'
import { biThreeDots } from '@quasar/extras/bootstrap-icons'
import AccountBase from './AccountBase.vue'
import { useElementSize } from '@vueuse/core'
import { tableBreakPoints } from '../constants'
import { tableColumns } from '../constants'

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

const removingTables = (currendWidth) => {
    const { value: width } = currendWidth
    if (width < 300) {
        return visibleColumns.value = tableBreakPoints[300]
    }

    if (width < 400) {
        return visibleColumns.value = tableBreakPoints[400]
    }

    if (width < 500) {
        return visibleColumns.value = tableBreakPoints[500]
    }

    if (width < 800) {
        return visibleColumns.value = tableBreakPoints[800]
    }

    if (width < 900) {
        return visibleColumns.value = tableBreakPoints[900]
    }

    if (width < 1000) {
        return visibleColumns.value = tableBreakPoints[1000]
    }

    if (width < 1300) {
        return visibleColumns.value = tableBreakPoints[1300]
    }
}

const unvisibleColumns = ref([])
const visibleColumns = ref([])

watch(width, () => removingTables(width), { deep: true })
onMounted(() => removingTables(width))

</script>

<template>
    <account-base>
        <div class="box" ref="box">
            <q-table :rows="rows" :columns="tableColumns" row-key="name" hide-bottom flat :visible-columns="visibleColumns">

                <template v-slot:body="props">
                    <q-tr :props="props">
                        <q-td auto-width>
                            <q-btn size="sm" color="accent" round dense @click="props.expand = !props.expand"
                                :icon="props.expand ? 'remove' : 'add'"></q-btn>
                        </q-td>
                        <q-td v-for="col in props.cols" :key="col.name" :props="props">
                            {{ col.value }}
                        </q-td>
                    </q-tr>
                    <q-tr v-show="props.expand" :props="props">
                        <q-td colspan="100%">
                            <div class="text-left">This is expand slot for row above: {{ props.row.name }}.</div>
                        </q-td>
                    </q-tr>
                </template>

                <template v-slot:body-cell-btn="props">
                    dadasd
                    <q-td :props="props" auto-width>
                        <q-btn size="sm" color="accent" round dense @click="props.expand = !props.expand"
                            :icon="props.expand ? 'remove' : 'add'"></q-btn>
                    </q-td>
                </template>

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
        </div>
    </account-base>
</template>

