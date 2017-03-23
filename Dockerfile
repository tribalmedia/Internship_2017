FROM centos:6

MAINTAINER "s-takada" <s-takada@tribalmedia.co.jp>

RUN yum -y update && yum clean all

RUN yum -y reinstall glibc-common && yum -y clean all
RUN localedef -v -c -i ja_JP -f UTF-8 ja_JP.UTF-8; echo "";
ENV LANG=ja_JP.UTF-8
RUN rm -f /etc/localtime
RUN ln -fs /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

RUN yum -y install git zip unzip && yum -y clean all

RUN yum install -y http://rpms.famillecollet.com/enterprise/remi-release-6.rpm && \
    yum clean all
RUN sed -i -e "s/enabled *= *1/enabled=0/g" /etc/yum.repos.d/remi.repo

RUN yum -y install httpd httpd-tools && yum -y clean all

RUN yum -y install --enablerepo=remi-php56, php php-cli php-common php-devel php-gd php-mbstring php-pdo php-pgsql php-mcrypt php-pear php-xml && yum -y clean all

EXPOSE 80

CMD ["/usr/sbin/httpd","-D","FOREGROUND"]