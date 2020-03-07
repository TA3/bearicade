# bearicade_ansible

### Building CentOS image from Dockerfile
- `docker build --rm --no-cache -t centos_bear .`
- `docker run -d -it -v /sys/fs/cgroup:/sys/fs/cgroup:ro centos_bear`
- `docker exec -it <container_id> /bin/bash`