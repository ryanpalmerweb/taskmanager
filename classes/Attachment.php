<?php
class Attachment
{
    private $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }

    // insert attachment into the database
    public function insertAttachment($taskId, $attachmentType, $attachmentBody)
    {
        $stmt = $this->db->prepare("INSERT INTO task_attachments (task_id, attachment_type, body) VALUES (:id, :type, :body)");
        $stmt->bindParam(':id', $taskId);
        $stmt->bindParam(':type', $attachmentType);
        $stmt->bindParam(':body', $attachmentBody);
        return $stmt->execute();
    }

    // get all attachments
    public function getAttachments($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM task_attachments WHERE task_id = :id ORDER BY created_at DESC");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // delete attachment
    public function deleteAttachment($id)
    {
        $getType = $this->db->prepare("SELECT attachment_type FROM task_attachments WHERE id = :id");
        $getType->bindParam(':id', $id);
        $getType->execute();
        $type = $getType->fetchColumn();
        if ($type == 'file') { 
            return $this->deleteFile($id); 
        }

        $stmt = $this->db->prepare("DELETE FROM task_attachments WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // delete file attachment
    private function deleteFile($id) {
        $getName = $this->db->prepare("SELECT body FROM task_attachments WHERE id = :id");
        $getName->bindParam(':id', $id);
        $getName->execute();
        $fileName = $getName->fetchColumn();
        // if unlinking the file is unsuccessful, return false at this stage
        $didDelete = unlink(__DIR__ . '/../files/' . $fileName);
        if (!$didDelete) { return false; }

        $stmt = $this->db->prepare("DELETE FROM task_attachments WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

}