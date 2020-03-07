<p align="center"><img width="150px" align="center" src="https://bearicade.ta3.dev/assets/imgs/bear_git.gif"></p>


  
# Bearicade 
![Version](https://img.shields.io/badge/version-0.1-blue.svg?cacheSeconds=2592000) ![Documentation](https://img.shields.io/badge/documentation-yes-brightgreen.svg) ![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)
> Open-souce secure gateway for distributed system

Bearicade (pronounced `be(ə)riˌkād` from bear and ~~barr~~icade) is an MIT-licensed open-source project. Based on a Ph.D thesis, meaning that beaicade is developed solely by one developer (for now).
Bearicade will require the community's contribution to reach a reliable state.

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
2. Clone bearicade git repository
```bash
git clone https://github.com/TA3/bearicade
```
3. Edit config.yml to suit your preference
```bash
vi bearicade/bearicade-ansible/config.yml
```
4. Run the ansible playbook
```bash
ansible-playbook -i bearicade/bearicade-ansible/hosts bearicade/bearicade-ansible/bear_install.yml
```

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
