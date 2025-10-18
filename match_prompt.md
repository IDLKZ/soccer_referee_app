При создании матча:
Создать сам матч при этом добавив следующие поля в MatchEntity
Также добавить поля указанные JudgeRequirementEntity (обязательные, без них нельзя создать Матч, при этом добавить возможность добавить несколько JudgeRequirementEntity)
Также добавить множественных созданий MatchDeadlines (не обязательные, при этом добавить возможность добавить несколько MatchDeadlineEntity)
Также выделить MatchLogistEntity (обязательные, без них нельзя создать Матч, при этом добавить возможность добавить несколько MatchLogistEntity)
Отобразить на одной страницы с использованием livewire  Создадим MatchEntity CRUD как UserManagement создав вид на одной странице, CRUD через modal, тщательно изучи UserManagement user-management.blade.php и создай точно такой же но для MatchEntity, добавь также поддержку dark light theme для match-entity-management.blade.php resources/views/layouts/partials/sidebars/employee.blade.php добавим управление требованиями протоколов для судей сюда
Проверить Gate и добавить для ролей -> RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE и RoleConstants::ADMINISTRATOR
