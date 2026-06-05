<?php

namespace App\Services;

use App\Models\AnalyticsEvent;
use App\Models\Document;
use App\Models\Message;
use Carbon\Carbon;

class AnalyticsService
{
    public function logEvent(int $userId, string $eventType, ?int $documentId = null, ?array $data = null): void
    {
        AnalyticsEvent::create([
            'user_id'     => $userId,
            'document_id' => $documentId,
            'event_type'  => $eventType,
            'data'        => $data,
        ]);
    }

    public function getStats(int $userId, int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days);

        $events = AnalyticsEvent::where('user_id', $userId)
                                ->where('created_at', '>=', $startDate)
                                ->get();

        return [
            'total_documents'       => Document::where('user_id', $userId)->count(),
            'documents_uploaded'    => $events->where('event_type', 'document_uploaded')->count(),
            'documents_processed'   => $events->where('event_type', 'document_processed')->count(),
            'total_chats'          => \App\Models\Conversation::where('user_id', $userId)->count(),
            'chat_messages'        => $events->where('event_type', 'chat_message_sent')->count(),
            'security_threats'     => $events->where('event_type', 'security_threat_found')->count(),
            'embeddings_generated' => $events->where('event_type', 'embedding_generated')->count(),
            'avg_processing_time'  => $this->getAvgProcessingTime($userId, $startDate),
            'documents_by_date'    => $this->getDocumentsByDate($userId, $startDate),
            'chats_by_date'        => $this->getChatsByDate($userId, $startDate),
            'threat_types'         => $this->getThreatTypes($userId, $startDate),
        ];
    }

    private function getAvgProcessingTime(int $userId, Carbon $startDate): float
    {
        $events = AnalyticsEvent::where('user_id', $userId)
                                ->where('event_type', 'document_processed')
                                ->where('created_at', '>=', $startDate)
                                ->get();

        if ($events->isEmpty()) {
            return 0;
        }

        $totalTime = $events->sum(fn($e) => $e->data['processing_time'] ?? 0);
        return round($totalTime / $events->count(), 2);
    }

    private function getDocumentsByDate(int $userId, Carbon $startDate): array
    {
        $events = AnalyticsEvent::where('user_id', $userId)
                                ->where('event_type', 'document_uploaded')
                                ->where('created_at', '>=', $startDate)
                                ->selectRaw("DATE(created_at) as date, COUNT(*) as count")
                                ->groupBy('date')
                                ->get();

        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'date'  => $event->date,
                'count' => $event->count,
            ];
        }
        return $result;
    }

    private function getChatsByDate(int $userId, Carbon $startDate): array
    {
        $events = AnalyticsEvent::where('user_id', $userId)
                                ->where('event_type', 'chat_message_sent')
                                ->where('created_at', '>=', $startDate)
                                ->selectRaw("DATE(created_at) as date, COUNT(*) as count")
                                ->groupBy('date')
                                ->get();

        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'date'  => $event->date,
                'count' => $event->count,
            ];
        }
        return $result;
    }

    private function getThreatTypes(int $userId, Carbon $startDate): array
    {
        $threats = \App\Models\ScanResult::whereHas('document', function ($q) use ($userId) {
                                            $q->where('user_id', $userId);
                                        })
                                        ->where('created_at', '>=', $startDate)
                                        ->selectRaw("finding_type, severity, COUNT(*) as count")
                                        ->groupBy('finding_type', 'severity')
                                        ->get();

        $result = [];
        foreach ($threats as $threat) {
            $result[] = [
                'type'     => $threat->finding_type,
                'severity' => $threat->severity,
                'count'    => $threat->count,
            ];
        }
        return $result;
    }
}