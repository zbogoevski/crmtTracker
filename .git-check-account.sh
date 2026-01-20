#!/bin/bash
# Скрипта за проверка на git конфигурацијата за овој проект

echo "=== Git конфигурација за овој проект ==="
echo ""
echo "Локална конфигурација (овој проект):"
echo "  Име: $(git config --local user.name)"
echo "  Email: $(git config --local user.email)"
echo ""
echo "=== Проверка на GitHub акаунт ==="
echo ""
echo "SSH аутентификација (github-zbogoevski):"
ssh -T git@github-zbogoevski 2>&1 | head -1
echo ""
echo "=== Git Remote конфигурација ==="
echo ""
if git remote get-url origin &>/dev/null; then
    echo "Origin remote:"
    git remote get-url origin
    echo ""
    if [[ $(git remote get-url origin) == *"github-zbogoevski"* ]]; then
        echo "✅ Remote е конфигуриран да користи правилниот SSH акаунт"
    else
        echo "⚠️  Remote не користи github-zbogoevski host"
        echo "   За да го поправиш, изврши:"
        echo "   git remote set-url origin git@github-zbogoevski:zbogoevski/crmtTracker.git"
    fi
else
    echo "⚠️  Нема remote конфигуриран"
    echo "   За да додадеш remote, изврши:"
    echo "   git remote add origin git@github-zbogoevski:zbogoevski/crmtTracker.git"
fi
echo ""
echo "=== Инструкции ==="
echo "1. Создади нов репозиториум на GitHub под zbogoevski акаунтот"
echo "2. Постави remote: git remote add origin git@github-zbogoevski:zbogoevski/REPO_NAME.git"
echo "3. Push: git push -u origin main (или master)"
