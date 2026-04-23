# Tiba AI — GitHub + ahost.uz Deploy

## Umumiy jarayon:
```
Lokal kompyuter → GitHub → ahost.uz server
     (git push)       (cPanel git pull)
```

---

## 📌 1-BOSQICH: GitHub Repo Yaratish (bir marta)

### 1.1. GitHub da yangi repo yarating
1. https://github.com/new ga o'ting
2. **Repository name:** `tiba-ai`
3. **Private** tanlanganiga ishonch hosil qiling (kodingiz yashirin bo'lsin)
4. **Create repository** bosing

### 1.2. Lokal Git o'rnatish
Agar Git o'rnatilmagan bo'lsa:
- https://git-scm.com/download/win dan yuklab o'rnating

Terminal (PowerShell) da:
```powershell
cd d:\887779999\tiba-ai

git init
git add .
git commit -m "Birinchi commit"
git branch -M main
git remote add origin https://github.com/SIZNING_USERNAME/tiba-ai.git
git push -u origin main
```

> ⚠️ `SIZNING_USERNAME` ni GitHub username bilan almashtiring

---

## 📌 2-BOSQICH: cPanel da Git Ulash (bir marta)

### 2.1. cPanel → Git Version Control
1. ahost.uz cPanel ga kiring
2. **Git™ Version Control** ni toping va oching
3. **Create** bosing

### 2.2. Repo sozlamalari
- **Clone URL:** `https://github.com/SIZNING_USERNAME/tiba-ai.git`
- **Repository Path:** `/home/CPANEL_USER/repositories/tiba-ai`
- **Deploy Path:** `/home/CPANEL_USER/public_html`
- **Create** bosing

> 💡 `CPANEL_USER` — cPanel dagi foydalanuvchi nomingiz (ahost.uz bergan)

### 2.3. Private repo uchun (SSH key yoki token)
Agar repo **Private** bo'lsa, cPanel dan GitHub ga kirish kerak:

**Usul 1: GitHub Token (osonroq)**
1. GitHub → Settings → Developer settings → Personal access tokens → **Generate new token**
2. `repo` permissionni belgilang → Token nusxalang
3. Clone URL ni shunday yozing:
   ```
   https://TOKEN@github.com/SIZNING_USERNAME/tiba-ai.git
   ```

**Usul 2: SSH Key**
1. cPanel → Terminal:
   ```bash
   ssh-keygen -t ed25519 -C "tibaai"
   cat ~/.ssh/id_ed25519.pub
   ```
2. Chiqgan kalitni GitHub → Settings → SSH Keys ga qo'shing
3. Clone URL: `git@github.com:SIZNING_USERNAME/tiba-ai.git`

---

## 📌 3-BOSQICH: `.cpanel.yml` yaratish (bir marta)

Bu fayl cPanel ga qaysi fayllarni qayerga deploy qilishni aytadi.

`.cpanel.yml` fayli allaqachon loyihada bo'lishi kerak (pastda yaratiladi).

---

## 🚀 HAR KUNLIK DEPLOY (2 buyruq)

Kod o'zgartirganingizdan keyin:

```powershell
cd d:\887779999\tiba-ai
git add .
git commit -m "Yangilanish tavsifi"
git push
```

**Tamom!** cPanel avtomatik yangi kodni tortib oladi va deploy qiladi.

### Agar cPanel avtomatik pull qilmasa:
cPanel → **Git Version Control** → tiba-ai → **Update from Remote** → **Pull or Deploy**

---

## ⚠️ Muhim eslatmalar

| Mavzu | Tafsilot |
|-------|---------|
| `.env` | GitHub ga **YUKLANMAYDI** (gitignore da). Serverda alohida yarating! |
| `data/*.db` | Baza ham yuklanmaydi. Serverda `init.php` orqali yarating |
| `generated/` | Rasmlar yuklanmaydi. Server o'zi yaratadi |
| `tmp/` | Vaqtinchalik fayllar yuklanmaydi |
