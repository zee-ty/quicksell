# Copy and setup entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Start Apache via custom entrypoint
CMD ["/usr/local/bin/docker-entrypoint.sh"]