output "migration_task_arn" {
  value = aws_dms_replication_task.migration_task.replication_task_arn
}