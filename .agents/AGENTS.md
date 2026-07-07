# Project Rules

## Automatic Git Commit & Push
- Setiap kali sebuah tugas (task/phase) selesai dikerjakan atau diimplementasikan, wajib secara otomatis melakukan proses git staging, commit, dan push (`git add .`, `git commit -m "..."`, dan `git push`) ke remote repository tanpa perlu diminta kembali oleh user.

## Automatic Rule Recording & README Synchronization
- Setiap kali ada perubahan aturan atau kebijakan baru proyek yang ditentukan oleh user, wajib langsung mencatatnya ke dalam berkas `.agents/AGENTS.md` ini.
- Setelah mencatat aturan baru di `.agents/AGENTS.md`, wajib secara otomatis memperbarui dokumen `README.md` (misalnya pada bagian Kontribusi atau Aturan Pengembangan) agar dokumentasi selalu sinkron dengan aturan terbaru.
