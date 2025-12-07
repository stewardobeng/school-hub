import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { DashboardLayout } from "@/components/layout/DashboardLayout";
import { StatCard } from "@/components/dashboard/StatCard";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Badge } from "@/components/ui/badge";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from "@/components/ui/alert-dialog";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { FileText, Plus, Search, MoreVertical, Calendar, Clock, Users, CheckCircle2, Edit, Trash2, Eye, Printer } from "lucide-react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { useToast } from "@/components/ui/use-toast";

const initialExams = [
  {
    id: "EXM001",
    title: "Midterm Exam - Mathematics",
    course: "MATH-101",
    grade: "Grade 10",
    date: "2024-02-15",
    time: "9:00 AM - 11:00 AM",
    duration: "2 hours",
    students: 30,
    status: "Scheduled",
    type: "Midterm",
  },
  {
    id: "EXM002",
    title: "Final Exam - Science",
    course: "SCI-201",
    grade: "Grade 11",
    date: "2024-03-20",
    time: "10:00 AM - 12:00 PM",
    duration: "2 hours",
    students: 25,
    status: "Scheduled",
    type: "Final",
  },
  {
    id: "EXM003",
    title: "Quiz - English Literature",
    course: "ENG-101",
    grade: "Grade 9",
    date: "2024-01-25",
    time: "11:00 AM - 11:30 AM",
    duration: "30 min",
    students: 28,
    status: "Completed",
    type: "Quiz",
  },
  {
    id: "EXM004",
    title: "Unit Test - World History",
    course: "HIS-201",
    grade: "Grade 12",
    date: "2024-02-10",
    time: "2:00 PM - 3:30 PM",
    duration: "1.5 hours",
    students: 22,
    status: "Completed",
    type: "Unit Test",
  },
  {
    id: "EXM005",
    title: "Practical Exam - Computer Science",
    course: "CS-301",
    grade: "Grade 10",
    date: "2024-02-28",
    time: "1:00 PM - 3:00 PM",
    duration: "2 hours",
    students: 30,
    status: "Scheduled",
    type: "Practical",
  },
  {
    id: "EXM006",
    title: "Assessment - Physical Education",
    course: "PE-101",
    grade: "Grade 11",
    date: "2024-01-30",
    time: "3:00 PM - 4:00 PM",
    duration: "1 hour",
    students: 25,
    status: "In Progress",
    type: "Assessment",
  },
];

export default function AdminExam() {
  const navigate = useNavigate();
  const [exams, setExams] = useState(initialExams);
  const [searchQuery, setSearchQuery] = useState("");
  const [statusFilter, setStatusFilter] = useState("all");
  const [isAddDialogOpen, setIsAddDialogOpen] = useState(false);
  const [isEditDialogOpen, setIsEditDialogOpen] = useState(false);
  const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
  const [isResultsDialogOpen, setIsResultsDialogOpen] = useState(false);
  const [selectedExam, setSelectedExam] = useState<any>(null);
  const [formData, setFormData] = useState({
    title: "",
    course: "",
    grade: "",
    date: "",
    time: "",
    duration: "",
    type: "",
    status: "Scheduled",
  });
  const { toast } = useToast();

  const totalExams = exams.length;
  const upcomingExams = exams.filter(e => e.status === "Scheduled").length;
  const completedExams = exams.filter(e => e.status === "Completed").length;

  const filteredExams = exams.filter((exam) => {
    const matchesSearch = exam.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
      exam.course.toLowerCase().includes(searchQuery.toLowerCase());
    const matchesStatus = statusFilter === "all" || exam.status.toLowerCase() === statusFilter.toLowerCase();
    return matchesSearch && matchesStatus;
  });

  const handleAddSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const newExam = {
      id: `EXM${String(exams.length + 1).padStart(3, '0')}`,
      ...formData,
      students: 0,
    };
    setExams([...exams, newExam]);
    toast({
      title: "Exam Scheduled",
      description: `${formData.title} has been successfully scheduled.`,
    });
    setIsAddDialogOpen(false);
    resetForm();
  };

  const handleEditSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedExam) return;
    
    const updatedExams = exams.map((e) =>
      e.id === selectedExam.id
        ? { ...e, ...formData }
        : e
    );
    setExams(updatedExams);
    toast({
      title: "Exam Updated",
      description: `${formData.title} has been successfully updated.`,
    });
    setIsEditDialogOpen(false);
    setSelectedExam(null);
    resetForm();
  };

  const handleDelete = () => {
    if (!selectedExam) return;
    
    setExams(exams.filter((e) => e.id !== selectedExam.id));
    toast({
      title: "Exam Cancelled",
      description: `${selectedExam.title} has been cancelled.`,
      variant: "destructive",
    });
    setIsDeleteDialogOpen(false);
    setSelectedExam(null);
  };

  const handleEdit = (exam: any) => {
    setSelectedExam(exam);
    setFormData({
      title: exam.title,
      course: exam.course,
      grade: exam.grade,
      date: exam.date,
      time: exam.time.split(' - ')[0],
      duration: exam.duration,
      type: exam.type,
      status: exam.status,
    });
    setIsEditDialogOpen(true);
  };

  const handleDeleteClick = (exam: any) => {
    setSelectedExam(exam);
    setIsDeleteDialogOpen(true);
  };

  const handleViewResults = (exam: any) => {
    setSelectedExam(exam);
    setIsResultsDialogOpen(true);
  };

  const handlePrintSchedule = (exam: any) => {
    toast({
      title: "Printing Schedule",
      description: `Printing schedule for ${exam.title}...`,
    });
    // In a real app, this would trigger a print dialog
    window.print();
  };

  const resetForm = () => {
    setFormData({
      title: "",
      course: "",
      grade: "",
      date: "",
      time: "",
      duration: "",
      type: "",
      status: "Scheduled",
    });
  };

  return (
    <DashboardLayout title="Exam" userRole="admin">
      {/* Stats Row */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <StatCard
          icon={<FileText className="w-6 h-6 text-blue-600" />}
          iconBgColor="bg-blue-100"
          label="Total Exams"
          value={totalExams}
        />
        <StatCard
          icon={<Calendar className="w-6 h-6 text-purple-600" />}
          iconBgColor="bg-purple-100"
          label="Upcoming"
          value={upcomingExams}
        />
        <StatCard
          icon={<CheckCircle2 className="w-6 h-6 text-green-600" />}
          iconBgColor="bg-green-100"
          label="Completed"
          value={completedExams}
        />
      </div>

      {/* Main Content */}
      <Card>
        <CardHeader>
          <div className="flex items-center justify-between">
            <div>
              <CardTitle>Exam Management</CardTitle>
              <p className="text-sm text-muted-foreground mt-1">
                Schedule and manage examinations
              </p>
            </div>
            <div className="flex items-center gap-3">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <Input
                  placeholder="Search exams..."
                  className="pl-10 w-64"
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                />
              </div>
              <Select value={statusFilter} onValueChange={setStatusFilter}>
                <SelectTrigger className="w-40">
                  <SelectValue placeholder="Filter" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">All Status</SelectItem>
                  <SelectItem value="scheduled">Scheduled</SelectItem>
                  <SelectItem value="in progress">In Progress</SelectItem>
                  <SelectItem value="completed">Completed</SelectItem>
                </SelectContent>
              </Select>
              <Button onClick={() => setIsAddDialogOpen(true)}>
                <Plus className="w-4 h-4 mr-2" />
                Schedule Exam
              </Button>
            </div>
          </div>
        </CardHeader>
        <CardContent>
          <div className="rounded-md border">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Exam</TableHead>
                  <TableHead>Course</TableHead>
                  <TableHead>Grade</TableHead>
                  <TableHead>Date & Time</TableHead>
                  <TableHead>Duration</TableHead>
                  <TableHead>Students</TableHead>
                  <TableHead>Type</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead className="text-right">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {filteredExams.map((exam) => (
                  <TableRow key={exam.id}>
                    <TableCell>
                      <div>
                        <div className="font-medium">{exam.title}</div>
                        <div className="text-sm text-muted-foreground mt-1">
                          ID: {exam.id}
                        </div>
                      </div>
                    </TableCell>
                    <TableCell>
                      <Badge variant="outline" className="font-mono">
                        {exam.course}
                      </Badge>
                    </TableCell>
                    <TableCell>{exam.grade}</TableCell>
                    <TableCell>
                      <div>
                        <div className="flex items-center gap-2">
                          <Calendar className="w-4 h-4 text-muted-foreground" />
                          <span className="font-medium">{exam.date}</span>
                        </div>
                        <div className="flex items-center gap-2 mt-1 text-sm text-muted-foreground">
                          <Clock className="w-3 h-3" />
                          {exam.time}
                        </div>
                      </div>
                    </TableCell>
                    <TableCell>
                      <Badge variant="secondary">{exam.duration}</Badge>
                    </TableCell>
                    <TableCell>
                      <div className="flex items-center gap-2">
                        <Users className="w-4 h-4 text-muted-foreground" />
                        <span className="font-medium">{exam.students}</span>
                      </div>
                    </TableCell>
                    <TableCell>
                      <Badge variant="outline">{exam.type}</Badge>
                    </TableCell>
                    <TableCell>
                      <Badge
                        variant={
                          exam.status === "Completed"
                            ? "default"
                            : exam.status === "In Progress"
                            ? "secondary"
                            : "outline"
                        }
                        className={
                          exam.status === "Completed"
                            ? "bg-green-500"
                            : exam.status === "In Progress"
                            ? "bg-blue-500"
                            : ""
                        }
                      >
                        {exam.status}
                      </Badge>
                    </TableCell>
                    <TableCell className="text-right">
                      <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                          <Button variant="ghost" size="icon">
                            <MoreVertical className="w-4 h-4" />
                          </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                          <DropdownMenuItem onClick={() => navigate(`/admin/exam/${exam.id}`)}>
                            <Eye className="w-4 h-4 mr-2" />
                            View Details
                          </DropdownMenuItem>
                          <DropdownMenuItem onClick={() => handleEdit(exam)}>
                            <Edit className="w-4 h-4 mr-2" />
                            Edit Exam
                          </DropdownMenuItem>
                          <DropdownMenuItem onClick={() => handleViewResults(exam)}>
                            <CheckCircle2 className="w-4 h-4 mr-2" />
                            View Results
                          </DropdownMenuItem>
                          <DropdownMenuItem onClick={() => handlePrintSchedule(exam)}>
                            <Printer className="w-4 h-4 mr-2" />
                            Print Schedule
                          </DropdownMenuItem>
                          <DropdownMenuItem
                            className="text-destructive"
                            onClick={() => handleDeleteClick(exam)}
                          >
                            <Trash2 className="w-4 h-4 mr-2" />
                            Cancel
                          </DropdownMenuItem>
                        </DropdownMenuContent>
                      </DropdownMenu>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </div>
        </CardContent>
      </Card>

      {/* Schedule Exam Dialog */}
      <Dialog open={isAddDialogOpen} onOpenChange={setIsAddDialogOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle>Schedule New Exam</DialogTitle>
            <DialogDescription>
              Enter the exam details to schedule a new examination.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleAddSubmit}>
            <div className="grid gap-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="title">Exam Title *</Label>
                <Input
                  id="title"
                  required
                  value={formData.title}
                  onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                  placeholder="Midterm Exam - Mathematics"
                />
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="course">Course *</Label>
                  <Select
                    value={formData.course}
                    onValueChange={(value) => setFormData({ ...formData, course: value })}
                    required
                  >
                    <SelectTrigger id="course">
                      <SelectValue placeholder="Select course" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="MATH-101">MATH-101</SelectItem>
                      <SelectItem value="SCI-201">SCI-201</SelectItem>
                      <SelectItem value="ENG-101">ENG-101</SelectItem>
                      <SelectItem value="HIS-201">HIS-201</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="grade">Grade *</Label>
                  <Select
                    value={formData.grade}
                    onValueChange={(value) => setFormData({ ...formData, grade: value })}
                    required
                  >
                    <SelectTrigger id="grade">
                      <SelectValue placeholder="Select grade" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Grade 9">Grade 9</SelectItem>
                      <SelectItem value="Grade 10">Grade 10</SelectItem>
                      <SelectItem value="Grade 11">Grade 11</SelectItem>
                      <SelectItem value="Grade 12">Grade 12</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="date">Date *</Label>
                  <Input
                    id="date"
                    type="date"
                    required
                    value={formData.date}
                    onChange={(e) => setFormData({ ...formData, date: e.target.value })}
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="time">Time *</Label>
                  <Input
                    id="time"
                    type="time"
                    required
                    value={formData.time}
                    onChange={(e) => setFormData({ ...formData, time: e.target.value })}
                  />
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="duration">Duration *</Label>
                  <Input
                    id="duration"
                    required
                    value={formData.duration}
                    onChange={(e) => setFormData({ ...formData, duration: e.target.value })}
                    placeholder="2 hours"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="type">Exam Type *</Label>
                  <Select
                    value={formData.type}
                    onValueChange={(value) => setFormData({ ...formData, type: value })}
                    required
                  >
                    <SelectTrigger id="type">
                      <SelectValue placeholder="Select type" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Midterm">Midterm</SelectItem>
                      <SelectItem value="Final">Final</SelectItem>
                      <SelectItem value="Quiz">Quiz</SelectItem>
                      <SelectItem value="Unit Test">Unit Test</SelectItem>
                      <SelectItem value="Practical">Practical</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsAddDialogOpen(false)}>
                Cancel
              </Button>
              <Button type="submit">
                <Plus className="w-4 h-4 mr-2" />
                Schedule Exam
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Edit Exam Dialog */}
      <Dialog open={isEditDialogOpen} onOpenChange={setIsEditDialogOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle>Edit Exam</DialogTitle>
            <DialogDescription>
              Update the exam details.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleEditSubmit}>
            <div className="grid gap-4 py-4">
              <div className="space-y-2">
                <Label htmlFor="edit-title">Exam Title *</Label>
                <Input
                  id="edit-title"
                  required
                  value={formData.title}
                  onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                  placeholder="Midterm Exam - Mathematics"
                />
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="edit-course">Course *</Label>
                  <Select
                    value={formData.course}
                    onValueChange={(value) => setFormData({ ...formData, course: value })}
                    required
                  >
                    <SelectTrigger id="edit-course">
                      <SelectValue placeholder="Select course" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="MATH-101">MATH-101</SelectItem>
                      <SelectItem value="SCI-201">SCI-201</SelectItem>
                      <SelectItem value="ENG-101">ENG-101</SelectItem>
                      <SelectItem value="HIS-201">HIS-201</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-grade">Grade *</Label>
                  <Select
                    value={formData.grade}
                    onValueChange={(value) => setFormData({ ...formData, grade: value })}
                    required
                  >
                    <SelectTrigger id="edit-grade">
                      <SelectValue placeholder="Select grade" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Grade 9">Grade 9</SelectItem>
                      <SelectItem value="Grade 10">Grade 10</SelectItem>
                      <SelectItem value="Grade 11">Grade 11</SelectItem>
                      <SelectItem value="Grade 12">Grade 12</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
              <div className="grid grid-cols-3 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="edit-date">Date *</Label>
                  <Input
                    id="edit-date"
                    type="date"
                    required
                    value={formData.date}
                    onChange={(e) => setFormData({ ...formData, date: e.target.value })}
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-time">Time *</Label>
                  <Input
                    id="edit-time"
                    type="time"
                    required
                    value={formData.time}
                    onChange={(e) => setFormData({ ...formData, time: e.target.value })}
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-status">Status *</Label>
                  <Select
                    value={formData.status}
                    onValueChange={(value) => setFormData({ ...formData, status: value })}
                    required
                  >
                    <SelectTrigger id="edit-status">
                      <SelectValue placeholder="Select status" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Scheduled">Scheduled</SelectItem>
                      <SelectItem value="In Progress">In Progress</SelectItem>
                      <SelectItem value="Completed">Completed</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="edit-duration">Duration *</Label>
                  <Input
                    id="edit-duration"
                    required
                    value={formData.duration}
                    onChange={(e) => setFormData({ ...formData, duration: e.target.value })}
                    placeholder="2 hours"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-type">Exam Type *</Label>
                  <Select
                    value={formData.type}
                    onValueChange={(value) => setFormData({ ...formData, type: value })}
                    required
                  >
                    <SelectTrigger id="edit-type">
                      <SelectValue placeholder="Select type" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Midterm">Midterm</SelectItem>
                      <SelectItem value="Final">Final</SelectItem>
                      <SelectItem value="Quiz">Quiz</SelectItem>
                      <SelectItem value="Unit Test">Unit Test</SelectItem>
                      <SelectItem value="Practical">Practical</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsEditDialogOpen(false)}>
                Cancel
              </Button>
              <Button type="submit">
                <Edit className="w-4 h-4 mr-2" />
                Update Exam
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Cancel Exam Confirmation Dialog */}
      <AlertDialog open={isDeleteDialogOpen} onOpenChange={setIsDeleteDialogOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Cancel Exam?</AlertDialogTitle>
            <AlertDialogDescription>
              This will cancel <span className="font-semibold">{selectedExam?.title}</span>. 
              This action cannot be undone.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Keep Scheduled</AlertDialogCancel>
            <AlertDialogAction
              onClick={handleDelete}
              className="bg-destructive text-destructive-foreground hover:bg-destructive/90"
            >
              <Trash2 className="w-4 h-4 mr-2" />
              Cancel Exam
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>

      {/* View Results Dialog */}
      <Dialog open={isResultsDialogOpen} onOpenChange={setIsResultsDialogOpen}>
        <DialogContent className="max-w-3xl">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              <CheckCircle2 className="w-5 h-5" />
              Exam Results - {selectedExam?.title}
            </DialogTitle>
            <DialogDescription>
              View exam results and student scores.
            </DialogDescription>
          </DialogHeader>
          <div className="py-4">
            {selectedExam && (
              <div className="rounded-md border">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Student ID</TableHead>
                      <TableHead>Name</TableHead>
                      <TableHead>Score</TableHead>
                      <TableHead>Grade</TableHead>
                      <TableHead>Status</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {selectedExam.status === "Completed" ? (
                      [
                        { id: "ST001", name: "John Smith", score: 92, maxScore: 100, grade: "A" },
                        { id: "ST002", name: "Emily Johnson", score: 85, maxScore: 100, grade: "B+" },
                        { id: "ST003", name: "Michael Brown", score: 78, maxScore: 100, grade: "C+" },
                      ].map((result) => (
                        <TableRow key={result.id}>
                          <TableCell className="font-mono text-sm">{result.id}</TableCell>
                          <TableCell className="font-medium">{result.name}</TableCell>
                          <TableCell>
                            <span className="font-semibold">
                              {result.score}/{result.maxScore}
                            </span>
                          </TableCell>
                          <TableCell>
                            <Badge
                              variant={result.grade.startsWith("A") ? "default" : "secondary"}
                              className={result.grade.startsWith("A") ? "bg-green-500" : ""}
                            >
                              {result.grade}
                            </Badge>
                          </TableCell>
                          <TableCell>
                            <Badge variant="outline">Completed</Badge>
                          </TableCell>
                        </TableRow>
                      ))
                    ) : (
                      <TableRow>
                        <TableCell colSpan={5} className="text-center text-muted-foreground py-8">
                          Results will be available after the exam is completed.
                        </TableCell>
                      </TableRow>
                    )}
                  </TableBody>
                </Table>
              </div>
            )}
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setIsResultsDialogOpen(false)}>
              Close
            </Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </DashboardLayout>
  );
}

