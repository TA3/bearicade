<p align="center"><img width="150px" align="center" src="https://bearicade.ta3.dev/assets/imgs/bear_git.gif"></p>


  
# Bearicade  ![Version](https://img.shields.io/badge/version-0.1-blue.svg?cacheSeconds=2592000) ![Documentation](https://img.shields.io/badge/documentation-yes-brightgreen.svg) ![License: MIT](https://img.shields.io/github/license/TA3/bearicade) ![Contributors](https://img.shields.io/github/contributors/TA3/bearicade)
> Open-souce secure gateway for distributed system

Bearicade (pronounced `be(ə)riˌkād` from bear and ~~barr~~icade) is an MIT-licensed open-source data-driven secure gateway for distributed system, built on a REST API, containerized via Docker and deployable with Ansible. 

Bearicade has been presented at the IEEE 19th International Conference on Trust, Security and Privacy in Computing and Communications [Paper](https://ieeexplore.ieee.org/document/9342969).

![Dashboard](https://bearicade.ta3.dev/assets/imgs/wireframe.svg)

## 🔖 Prerequisite
- CentOS/Red Hat >=7.4 
- Git
- Python >=2.7
- PIP >= 20.0.2
- Ansible >= 2.9.4
- Docker >= 1.13.1

## 🚀 Deploy
1. Install Prerequisite
```bash
sudo yum install epel-release git python python-pip docker && sudo pip install ansible
```
2. Enable and Start Docker 
```bash
sudo systemctl enable docker && systemctl start docker
```
3. Clone bearicade git repository
```bash
git clone https://github.com/TA3/bearicade
```
4. Generate Keys and Authorize the key
```bash
ssh-keygen && cat ~/.ssh/id_rsa.pub > ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys
```
5. Permit SSH Root Login
```bash
sed -i '/^PermitRootLogin/s/no/yes/' /etc/ssh/sshd_config && systemctl restart sshd
```
6. Edit config.yml to suit your preference
```bash
vi bearicade/bearicade_ansible/config.yml
```
7. Install bearicade with ansible playbook
```bash
ansible-playbook -i bearicade/bearicade_ansible/hosts bearicade/bearicade_ansible/bear_install.yml
```
8. Add Administrators with ansible playbook
```bash
ansible-playbook -i bearicade/bearicade_ansible/hosts bearicade/bearicade_ansible/bear_add_user.yml
```

## 🏠 Dashboard
![Dashboard](https://bearicade.ta3.dev/assets/imgs/bearicade-main.png)

## 🤝 Contributing

Contributions, issues and feature requests are welcome!<br />Feel free to check [issues page](https://github.com/TA3/bearicade/issues). 

## Show your support

Give a ⭐️ if this project helped you!

## License

[MIT](http://opensource.org/licenses/MIT) 

Copyright (c) 2017-present, Taha Al-Jody



- Website: https://ta3.dev
- Github: [@TA3](https://github.com/TA3)
- LinkedIn: [@tahaaljody](https://linkedin.com/in/tahaaljody)
