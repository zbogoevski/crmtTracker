# GitHub Setup за crmtTracker

## ✅ Конфигурација за zbogoevski акаунт

Овој проект е конфигуриран да користи **zbogoevski** GitHub акаунт со email `zoran.bogoevski@iwinback.com`.

### 🔐 SSH Конфигурација

SSH е конфигуриран да користи специфичен host за овој акаунт:

```
Host github-zbogoevski
  HostName github.com
  User git
  IdentityFile ~/.ssh/id_ed25519_work
```

### 📋 Git Конфигурација

Локалната git конфигурација за овој проект:

```bash
git config --local user.name "zoran"
git config --local user.email "zoran.bogoevski@iwinback.com"
```

### 🚀 Инструкции за Publish на GitHub

#### 1. Создади нов репозиториум на GitHub

1. Оди на https://github.com/new
2. Најави се како **zbogoevski** акаунт
3. Име на репозиториум: `crmtTracker` (или друго име)
4. Избери дали ќе биде public или private
5. **НЕ** креирај README, .gitignore или LICENSE (веќе ги имаме)

#### 2. Постави Git Remote

```bash
# Заменете REPO_NAME со вистинското име на репозиториумот
git remote add origin git@github-zbogoevski:zbogoevski/REPO_NAME.git
```

**Важно:** Користи `git@github-zbogoevski:` наместо `git@github.com:` за да се осигураш дека користи правилниот SSH клуч.

#### 3. Провери Remote конфигурација

```bash
git remote -v
```

Треба да видиш:
```
origin  git@github-zbogoevski:zbogoevski/REPO_NAME.git (fetch)
origin  git@github-zbogoevski:zbogoevski/REPO_NAME.git (push)
```

#### 4. Додади и Commit промени

```bash
# Додади сите промени
git add .

# Commit со порака
git commit -m "Initial commit: Modular Laravel Starter Kit"

# Провери дека користи правилниот акаунт
git log --pretty=format:"%h - %an (%ae)" -1
```

Треба да видиш: `zoran (zoran.bogoevski@iwinback.com)`

#### 5. Push на GitHub

```bash
# За main branch
git branch -M main
git push -u origin main

# Или за master branch
git push -u origin master
```

### ✅ Проверка

За да провериш дали сè е правилно конфигурирано:

```bash
./.git-check-account.sh
```

### 🔧 Ажурирање на Remote URL

Ако треба да го промениш remote URL:

```bash
# Провери моментален remote
git remote get-url origin

# Промени remote
git remote set-url origin git@github-zbogoevski:zbogoevski/REPO_NAME.git
```

### ⚠️ Важно

- **Секогаш** користи `git@github-zbogoevski:` наместо `git@github.com:` за овој проект
- Ова гарантира дека ќе користи правилниот SSH клуч (`id_ed25519_work`)
- Локалната git конфигурација е поставена само за овој проект
- Другите проекти на твојот Mac нема да бидат засегнати

### 🐛 Решавање на проблеми

#### Проблем: "Permission denied (publickey)"

```bash
# Провери дали SSH клучот е додаден
ssh-add ~/.ssh/id_ed25519_work

# Тестирај SSH конекција
ssh -T git@github-zbogoevski
```

#### Проблем: Push користи погрешен акаунт

```bash
# Провери git конфигурација
git config --local user.email

# Ако не е правилно, постави го повторно
git config --local user.email "zoran.bogoevski@iwinback.com"
```

#### Проблем: Remote користи погрешен SSH host

```bash
# Провери remote URL
git remote get-url origin

# Ако не користи github-zbogoevski, промени го
git remote set-url origin git@github-zbogoevski:zbogoevski/REPO_NAME.git
```
