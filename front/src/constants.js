const tableColumns = [
    { name: 'name', label: 'Наименование организации', field: 'name', align: 'left', sortable: true},
    { name: 'inn', label: 'ИНН', field: 'inn', align: 'left', hideFrom: 600, sortable: false },
    { name: 'vacancy', label: 'Вакансии', field: 'carbs', hideFrom: 850, sortable: false },
    { name: 'users', label: 'Пользователи', field: 'users', hideFrom: 1000, sortable: false },
    { name: 'feedback', label: 'Отклики в работе', field: 'feedback', hideFrom: 1100, sortable: false },
    { name: 'action', label: 'Действие', field: 'action', sortable: false },
    { name: 'user', label: 'Создатель аккаунта', field: 'user', align: 'left', hideFrom: 1200, sortable: false },
    { name: 'createdon', label: 'Дата создания', field: 'createdon', align: 'left', hideFrom: 1400, sortable: false },
]

const tableColumnsNames = ['name', 'inn', 'vacancy', 'users', 'feedback', 'action', 'user', 'createdon']

export { tableColumns, tableColumnsNames }