# Use a stock Docksal image as the base
FROM docksal/cli:2.11-php7.4

# Install the symfony CLI.
RUN \
   wget https://get.symfony.com/cli/installer -O - | bash && \
   mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# IMPORTANT! Switching back to the root user as the last instruction.
USER root