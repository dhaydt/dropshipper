git add .

read -p "Enter commit name: " x

git commit -m "${x}"
# echo "Welcome ${x}!"
git push origin main
