import { useState } from "react";
import { MoreHorizontal } from "lucide-react";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { cn } from "@/lib/utils";

interface Performer {
  id: string;
  first_name: string;
  last_name: string;
  grade: string;
  avg_score: number;
  exam_count: number;
}

interface TopPerformersProps {
  performers?: Performer[];
}

type Period = "Week" | "Month" | "Year";

export function TopPerformers({ performers: propPerformers = [] }: TopPerformersProps) {
  const [period, setPeriod] = useState<Period>("Week");

  // Transform API data to component format
  const performers = propPerformers.map((p) => ({
    name: `${p.first_name} ${p.last_name}`,
    id: p.id,
    class: p.grade || "N/A",
    score: parseFloat(p.avg_score.toFixed(2)),
    avatar: "",
  }));

  return (
    <div className="chart-card animate-fade-in" style={{ animationDelay: "0.3s" }}>
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-lg font-semibold text-foreground">Top Performer</h3>
        <button className="p-1 hover:bg-muted rounded">
          <MoreHorizontal className="w-5 h-5 text-muted-foreground" />
        </button>
      </div>

      {/* Period Tabs */}
      <div className="flex gap-4 mb-4 border-b border-border">
        {(["Week", "Month", "Year"] as Period[]).map((p) => (
          <button
            key={p}
            onClick={() => setPeriod(p)}
            className={cn(
              "pb-2 text-sm font-medium transition-colors relative",
              period === p 
                ? "text-foreground" 
                : "text-muted-foreground hover:text-foreground"
            )}
          >
            {p}
            {period === p && (
              <div className="absolute bottom-0 left-0 right-0 h-0.5 bg-accent" />
            )}
          </button>
        ))}
      </div>

      {/* Table Header */}
      <div className="grid grid-cols-5 gap-4 text-xs text-muted-foreground mb-3">
        <span>Photo</span>
        <span>Name</span>
        <span>ID Number</span>
        <span>Standard</span>
        <span>Rank</span>
      </div>

      {/* Performers List */}
      <div className="space-y-3">
        {performers.length === 0 ? (
          <div className="text-center text-muted-foreground py-8 text-sm">
            No performance data available yet.
          </div>
        ) : (
          performers.map((performer, index) => (
          <div key={index} className="grid grid-cols-5 gap-4 items-center">
            <Avatar className="w-8 h-8">
              <AvatarImage src={performer.avatar} />
              <AvatarFallback className="bg-muted text-xs">
                {performer.name.split(' ').map(n => n[0]).join('')}
              </AvatarFallback>
            </Avatar>
            <span className="text-sm font-medium text-foreground">{performer.name}</span>
            <span className="text-sm text-muted-foreground">ID: {performer.id}</span>
            <span className="text-sm text-muted-foreground">{performer.class}</span>
            <div className="flex items-center gap-2">
              <span className="text-sm font-medium text-accent">{performer.score}%</span>
              <div className="flex-1 h-1.5 bg-muted rounded-full overflow-hidden">
                <div 
                  className="h-full bg-accent rounded-full transition-all duration-500"
                  style={{ width: `${performer.score}%` }}
                />
              </div>
            </div>
          </div>
          ))
        )}
      </div>
    </div>
  );
}
