const tableBreakPoints = {
    300: ['name'],
    400: ['name', 'inn'],
    500: ['name', 'inn', 'vacancy'],
    800: ['name', 'inn', 'vacancy', 'users'],
    900: ['name', 'inn', 'vacancy', 'users', 'feedback'],
    1000: ['name', 'inn', 'vacancy', 'users', 'feedback', 'action'],
    1200: ['name', 'inn', 'vacancy', 'users', 'feedback', 'action', 'user'],
    1300: ['name', 'inn', 'vacancy', 'users', 'feedback', 'action', 'user', 'createdon'],
}

const tableColumns = [
    
    { name: 'name', label: 'Наименование организации', field: 'name', align: 'left' },
    { name: 'inn', label: 'ИНН', field: 'inn', align: 'left' },

    { name: 'btn', label: 'BTN', field: 'btn', align: 'left' },
    { name: 'vacancy', label: 'Вакансии', field: 'carbs' },
    { name: 'users', label: 'Пользователи', field: 'users' },
    { name: 'feedback', label: 'Отклики в работе', field: 'feedback' },
    { name: 'action', label: 'Действие', field: 'action' },
    { name: 'user', label: 'Создатель аккаунта', field: 'user', align: 'left' },
    { name: 'createdon', label: 'Дата создания', field: 'createdon', align: 'left' },
]

export { tableBreakPoints, tableColumns}