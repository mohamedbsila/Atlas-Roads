# Jenkins Server Requirements Setup

This guide will help you install all required tools on your Jenkins server for the Atlas-Roads pipeline.

## 🚀 Quick Setup Script

Run this on your Jenkins server (Ubuntu/Debian):

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 and extensions
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
    php8.2-curl php8.2-zip php8.2-gd php8.2-intl php8.2-bcmath php8.2-soap

# Install Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js 18.x and NPM
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install MySQL Client
sudo apt install -y mysql-client

# Install Git (if not already installed)
sudo apt install -y git

# Verify installations
echo "=== Checking Installations ==="
php -v
composer --version
node -v
npm -v
mysql --version
git --version

echo "✅ All tools installed successfully!"
```

---

## 📋 Manual Installation Steps

### 1. Install PHP 8.2

```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP and required extensions
sudo apt install -y php8.2 \
    php8.2-cli \
    php8.2-fpm \
    php8.2-mysql \
    php8.2-xml \
    php8.2-mbstring \
    php8.2-curl \
    php8.2-zip \
    php8.2-gd \
    php8.2-intl \
    php8.2-bcmath \
    php8.2-soap

# Verify PHP installation
php -v
```

### 2. Install Composer

```bash
# Download and install Composer
curl -sS https://getcomposer.org/installer -o composer-setup.php
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php

# Verify Composer installation
composer --version
```

### 3. Install Node.js and NPM

```bash
# Install Node.js 18.x
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Verify Node and NPM installation
node -v
npm -v
```

### 4. Install MySQL Client

```bash
# Install MySQL client for database operations
sudo apt install -y mysql-client

# Verify MySQL client installation
mysql --version
```

### 5. Configure Jenkins User Permissions

```bash
# Allow Jenkins user to run commands
sudo usermod -aG www-data jenkins

# Create required directories
sudo mkdir -p /var/lib/jenkins/.composer
sudo chown -R jenkins:jenkins /var/lib/jenkins/.composer
sudo mkdir -p /var/lib/jenkins/.npm
sudo chown -R jenkins:jenkins /var/lib/jenkins/.npm
```

---

## 🗄️ Database Configuration

### Option 1: Install MySQL Server on Jenkins Machine

```bash
# Install MySQL Server
sudo apt install -y mysql-server

# Secure MySQL installation
sudo mysql_secure_installation

# Create database user for Jenkins
sudo mysql -u root -p
```

Then in MySQL console:

```sql
CREATE USER 'jenkins_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON atlas_roads_test.* TO 'jenkins_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Option 2: Use Remote MySQL Server

If you have a remote MySQL server, ensure it allows connections from Jenkins server:

```sql
-- On remote MySQL server
CREATE USER 'jenkins_user'@'jenkins_server_ip' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON atlas_roads_test.* TO 'jenkins_user'@'jenkins_server_ip';
FLUSH PRIVILEGES;
```

Update firewall on MySQL server:
```bash
sudo ufw allow from jenkins_server_ip to any port 3306
```

---

## 🔐 Jenkins Credentials Setup

### 1. Database Credentials

**NOTE:** Le Jenkinsfile est configuré pour utiliser MySQL avec l'utilisateur `root` et un mot de passe vide.

**Aucune credential Jenkins n'est nécessaire pour la base de données!** ✅

Si vous souhaitez utiliser un utilisateur MySQL différent, modifiez ces lignes dans le Jenkinsfile:
- Ligne 103: `sed -i "s/DB_USERNAME=.*/DB_USERNAME=root/" .env`
- Ligne 104: `sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=/" .env`
- Ligne 207-208: `mysql -h${DB_HOST} -uroot ...`
- Ligne 375: `mysql -h${DB_HOST} -uroot ...`

### 2. Git Credentials (Already Configured)

You already have the `lara` credential configured ✅

### 3. SSH Deploy Key (Optional - for deployment)

If you want to enable deployment:

1. Generate SSH key on Jenkins server:
```bash
ssh-keygen -t rsa -b 4096 -f /var/lib/jenkins/.ssh/deploy-ssh-key -N ""
```

2. Add the public key to your deployment server:
```bash
cat /var/lib/jenkins/.ssh/deploy-ssh-key.pub
# Copy this and add to ~/.ssh/authorized_keys on deployment server
```

3. Add credential in Jenkins:
   - Kind: SSH Username with private key
   - ID: `deploy-ssh-key`
   - Username: Your deployment user
   - Private Key: Paste content of `/var/lib/jenkins/.ssh/deploy-ssh-key`

---

## 📧 Email Configuration (Optional)

To enable email notifications, configure SMTP in Jenkins:

**Jenkins → Manage Jenkins → Configure System → Extended E-mail Notification**

Example for Gmail:

- SMTP server: `smtp.gmail.com`
- SMTP port: `587`
- Use TLS: ✅
- Credentials: Add Gmail app password
- Default recipients: `team@example.com`

---

## ✅ Verification Checklist

After installation, verify everything is working:

```bash
# Switch to jenkins user
sudo su - jenkins

# Test commands
php -v
composer --version
node -v
npm -v
mysql --version
git --version

# Test MySQL connection
mysql -h127.0.0.1 -ujenkins_user -p atlas_roads_test -e "SELECT 1;"
```

---

## 🚀 After Installation

Once all tools are installed:

1. **Restart Jenkins** to recognize new tools:
```bash
sudo systemctl restart jenkins
```

2. **Update Jenkinsfile** (if needed) - Already done! ✅

3. **Run your pipeline again** in Jenkins

---

## 🔧 Troubleshooting

### PHP not found after installation

```bash
# Check PHP path
which php

# Add to PATH if needed (in Jenkins)
# Manage Jenkins → Configure System → Global properties
# Add Environment Variable:
# Name: PATH
# Value: /usr/bin:/usr/local/bin:$PATH
```

### Composer memory issues

```bash
# Increase PHP memory limit
sudo sed -i 's/memory_limit = .*/memory_limit = 2G/' /etc/php/8.2/cli/php.ini
```

### NPM permissions issues

```bash
# Fix NPM permissions
sudo chown -R jenkins:jenkins /var/lib/jenkins/.npm
sudo chown -R jenkins:jenkins /var/lib/jenkins/.config
```

### MySQL connection refused

```bash
# Check MySQL is running
sudo systemctl status mysql

# Check MySQL is listening
sudo netstat -tlnp | grep 3306

# Test connection
mysql -h127.0.0.1 -ujenkins_user -p
```

---

## 📚 Additional Resources

- [Jenkins Installation Guide](https://www.jenkins.io/doc/book/installing/)
- [PHP Installation](https://www.php.net/manual/en/install.php)
- [Composer Documentation](https://getcomposer.org/doc/)
- [Node.js Installation](https://nodejs.org/en/download/package-manager)

---

## 🎉 Success Indicators

Your pipeline should succeed when you see:

```
✅ PHP Version: 8.2.x
✅ Composer Version: 2.x
✅ Node Version: 18.x
✅ NPM Version: 9.x or 10.x
✅ MySQL Client: 8.x
✅ All stages passing (green checkmarks)
```

---

**Need Help?** Check the Jenkins console output for specific error messages and refer to the troubleshooting section above.


