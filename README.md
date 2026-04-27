# Храм Живоначальной Троицы в Остафьеве

Стек: DDEV, Vite, Кастомная тема

---

## 1. Локальная установка

### 1.1 Сначала обновляем систему:

Обновляем пакеты WSL
```bash
sudo apt update && sudo apt upgrade -y
```

Проверяем установку Node, npm и git:
```bash
node --version
npm --version
git --version
```
Если Node ниже 22 версии — обновляем.

Устанавливаем DDEV:
```bash
curl -fsSL https://raw.githubusercontent.com/ddev/ddev/master/scripts/install_ddev.sh | bash
```

Проверяем установку:
```bash
ddev version
```

Клонируем проект:
```bash
git clone https://github.com/dekobad228-web/Church.git
cd Church
```

Запускаем окружение:
```bash
ddev start
```

Устанавливаем PHP зависимости:
```bash
ddev composer install
```

### 1.2 Фронтенд (Vite)

Переходим в тему:
```bash
cd wp-content/themes/main
```

Устанавливаем зависимости:
```bash
npm install
```

Запускаем dev сервер:
```bash
npm run dev
```

### 1.3 База данных
Импорт базы данных:
```bash
ddev import-db --file=_db/db.sql
```

### 1.4 Запуск проекта

Команда запуска сайта:
```bash
ddev launch
```

Вручную: https://church.ddev.site
## 2. Структура проекта

```bash
.ddev — окружение проекта
wp-admin, wp-includes — ядро WordPress
wp-content/themes/main — основная тема
src — исходники фронтенда
dist — сборка фронтенда
PHP логика темы
Vite конфигурация
package.json
db.sql — база данных
```
## 3. Основные команды

DDEV:
```bash
ddev start
ddev stop
ddev restart
ddev launch
ddev import-db --file=_db/db.sql
ddev export-db --file=_db/db.sql
```

## 4. Взаимодействие с git

```bash
git branch - текущая ветка
git switch backend - переключение на ветку backend
git switch frontend - переключение на ветку frontend
git pull origin main - выгрузка последний изменений из main
git add . - добавить все изменения
git commit -m "update (ифнормация о комите)"
git push - запушить все изменения
```
