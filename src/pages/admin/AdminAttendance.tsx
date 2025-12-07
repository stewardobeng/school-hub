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
import { Calendar } from "@/components/ui/calendar";
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
import { ClipboardCheck, CheckCircle2, XCircle, Clock, Calendar as CalendarIcon, Edit, Trash2, Eye, MoreVertical } from "lucide-react";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { useToast } from "@/components/ui/use-toast";

const initialAttendanceData = [
  {
    id: "ATT001",
    date: "2024-01-15",
    class: "Grade 10 - Mathematics",
    teacher: "Dr. Robert Anderson",
    totalStudents: 30,
    present: 28,
    absent: 2,
    late: 0,
    status: "Completed",
  },
  {
    id: "ATT002",
    date: "2024-01-15",
    class: "Grade 11 - Science",
    teacher: "Prof. Maria Garcia",
    totalStudents: 25,
    present: 24,
    absent: 1,
    late: 0,
    status: "Completed",
  },
  {
    id: "ATT003",
    date: "2024-01-15",
    class: "Grade 9 - English",
    teacher: "Ms. Jennifer Lee",
    totalStudents: 28,
    present: 27,
    absent: 0,
    late: 1,
    status: "Completed",
  },
  {
    id: "ATT004",
    date: "2024-01-14",
    class: "Grade 12 - History",
    teacher: "Mr. James Taylor",
    totalStudents: 22,
    present: 20,
    absent: 2,
    late: 0,
    status: "Completed",
  },
  {
    id: "ATT005",
    date: "2024-01-14",
    class: "Grade 10 - Computer Science",
    teacher: "Dr. Lisa Chen",
    totalStudents: 30,
    present: 29,
    absent: 1,
    late: 0,
    status: "Completed",
  },
  {
    id: "ATT006",
    date: "2024-01-16",
    class: "Grade 11 - Physical Education",
    teacher: "Mr. Thomas White",
    totalStudents: 25,
    present: 0,
    absent: 0,
    late: 0,
    status: "Pending",
  },
];

export default function AdminAttendance() {
  const navigate = useNavigate();
  const [attendanceData, setAttendanceData] = useState(initialAttendanceData);
  const [date, setDate] = useState<Date | undefined>(new Date());
  const [classFilter, setClassFilter] = useState("all");
  const [isAddDialogOpen, setIsAddDialogOpen] = useState(false);
  const [isEditDialogOpen, setIsEditDialogOpen] = useState(false);
  const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
  const [isDetailsDialogOpen, setIsDetailsDialogOpen] = useState(false);
  const [selectedRecord, setSelectedRecord] = useState<any>(null);
  const [formData, setFormData] = useState({
    class: "",
    teacher: "",
    date: new Date().toISOString().split('T')[0],
    totalStudents: "",
    present: "",
    absent: "",
    late: "",
  });
  const { toast } = useToast();

  const overallAttendance = attendanceData.length > 0
    ? (attendanceData.reduce((sum, r) => sum + (r.present / r.totalStudents) * 100, 0) / attendanceData.length).toFixed(1)
    : "0.0";
  const todayPresent = attendanceData
    .filter(r => r.date === new Date().toISOString().split('T')[0])
    .reduce((sum, r) => sum + r.present, 0);
  const todayAbsent = attendanceData
    .filter(r => r.date === new Date().toISOString().split('T')[0])
    .reduce((sum, r) => sum + r.absent, 0);

  const filteredAttendance = attendanceData.filter((record) => {
    if (classFilter === "all") return true;
    return record.class.toLowerCase().includes(classFilter.toLowerCase());
  });

  const handleAddSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const newRecord = {
      id: `ATT${String(attendanceData.length + 1).padStart(3, '0')}`,
      ...formData,
      totalStudents: parseInt(formData.totalStudents),
      present: parseInt(formData.present),
      absent: parseInt(formData.absent),
      late: parseInt(formData.late || "0"),
      status: "Completed",
    };
    setAttendanceData([...attendanceData, newRecord]);
    toast({
      title: "Attendance Marked",
      description: `Attendance for ${formData.class} has been successfully recorded.`,
    });
    setIsAddDialogOpen(false);
    resetForm();
  };

  const handleEditSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedRecord) return;
    
    const updatedRecords = attendanceData.map((r) =>
      r.id === selectedRecord.id
        ? {
            ...r,
            ...formData,
            totalStudents: parseInt(formData.totalStudents),
            present: parseInt(formData.present),
            absent: parseInt(formData.absent),
            late: parseInt(formData.late || "0"),
          }
        : r
    );
    setAttendanceData(updatedRecords);
    toast({
      title: "Attendance Updated",
      description: `Attendance record has been successfully updated.`,
    });
    setIsEditDialogOpen(false);
    setSelectedRecord(null);
    resetForm();
  };

  const handleDelete = () => {
    if (!selectedRecord) return;
    
    setAttendanceData(attendanceData.filter((r) => r.id !== selectedRecord.id));
    toast({
      title: "Record Deleted",
      description: `Attendance record has been deleted.`,
      variant: "destructive",
    });
    setIsDeleteDialogOpen(false);
    setSelectedRecord(null);
  };

  const handleEdit = (record: any) => {
    setSelectedRecord(record);
    setFormData({
      class: record.class,
      teacher: record.teacher,
      date: record.date,
      totalStudents: record.totalStudents.toString(),
      present: record.present.toString(),
      absent: record.absent.toString(),
      late: record.late.toString(),
    });
    setIsEditDialogOpen(true);
  };

  const handleDeleteClick = (record: any) => {
    setSelectedRecord(record);
    setIsDeleteDialogOpen(true);
  };

  const handleViewDetails = (record: any) => {
    setSelectedRecord(record);
    setIsDetailsDialogOpen(true);
  };

  const resetForm = () => {
    setFormData({
      class: "",
      teacher: "",
      date: new Date().toISOString().split('T')[0],
      totalStudents: "",
      present: "",
      absent: "",
      late: "",
    });
  };

  return (
    <DashboardLayout title="Attendance" userRole="admin">
      {/* Stats Row */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <StatCard
          icon={<ClipboardCheck className="w-6 h-6 text-blue-600" />}
          iconBgColor="bg-blue-100"
          label="Overall Attendance"
          value={`${overallAttendance}%`}
        />
        <StatCard
          icon={<CheckCircle2 className="w-6 h-6 text-green-600" />}
          iconBgColor="bg-green-100"
          label="Present Today"
          value={todayPresent}
        />
        <StatCard
          icon={<XCircle className="w-6 h-6 text-red-600" />}
          iconBgColor="bg-red-100"
          label="Absent Today"
          value={todayAbsent}
        />
      </div>

      {/* Main Content Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {/* Calendar */}
        <Card className="lg:col-span-1">
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <CalendarIcon className="w-5 h-5" />
              Calendar
            </CardTitle>
          </CardHeader>
          <CardContent>
            <Calendar
              mode="single"
              selected={date}
              onSelect={setDate}
              className="rounded-md border-0"
            />
          </CardContent>
        </Card>

        {/* Attendance Records */}
        <Card className="lg:col-span-3">
          <CardHeader>
            <div className="flex items-center justify-between">
              <div>
                <CardTitle>Attendance Records</CardTitle>
                <p className="text-sm text-muted-foreground mt-1">
                  View and manage daily attendance
                </p>
              </div>
              <div className="flex items-center gap-3">
              <Select value={classFilter} onValueChange={setClassFilter}>
                <SelectTrigger className="w-40">
                  <SelectValue placeholder="Filter by class" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">All Classes</SelectItem>
                  <SelectItem value="grade9">Grade 9</SelectItem>
                  <SelectItem value="grade10">Grade 10</SelectItem>
                  <SelectItem value="grade11">Grade 11</SelectItem>
                  <SelectItem value="grade12">Grade 12</SelectItem>
                </SelectContent>
              </Select>
                <Button onClick={() => setIsAddDialogOpen(true)}>
                  <ClipboardCheck className="w-4 h-4 mr-2" />
                  Mark Attendance
                </Button>
              </div>
            </div>
          </CardHeader>
          <CardContent>
            <div className="rounded-md border">
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>Date</TableHead>
                    <TableHead>Class</TableHead>
                    <TableHead>Teacher</TableHead>
                    <TableHead>Total</TableHead>
                    <TableHead>Present</TableHead>
                    <TableHead>Absent</TableHead>
                    <TableHead>Late</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead className="text-right">Actions</TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {filteredAttendance.map((record) => {
                    const attendanceRate = record.totalStudents > 0
                      ? ((record.present / record.totalStudents) * 100).toFixed(1)
                      : "0.0";
                    return (
                      <TableRow key={record.id}>
                        <TableCell className="font-medium">{record.date}</TableCell>
                        <TableCell>{record.class}</TableCell>
                        <TableCell className="text-muted-foreground">{record.teacher}</TableCell>
                        <TableCell>{record.totalStudents}</TableCell>
                        <TableCell>
                          <div className="flex items-center gap-2">
                            <CheckCircle2 className="w-4 h-4 text-green-600" />
                            <span className="font-medium text-green-600">{record.present}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          <div className="flex items-center gap-2">
                            <XCircle className="w-4 h-4 text-red-600" />
                            <span className="font-medium text-red-600">{record.absent}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          <div className="flex items-center gap-2">
                            <Clock className="w-4 h-4 text-amber-600" />
                            <span className="font-medium text-amber-600">{record.late}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          <div className="flex items-center justify-between">
                            <div className="flex items-center gap-2">
                              <Badge
                                variant={record.status === "Completed" ? "default" : "secondary"}
                                className={record.status === "Completed" ? "bg-green-500" : ""}
                              >
                                {record.status}
                              </Badge>
                              <span className="text-xs text-muted-foreground">
                                ({attendanceRate}%)
                              </span>
                            </div>
                            <DropdownMenu>
                              <DropdownMenuTrigger asChild>
                                <Button variant="ghost" size="icon" className="h-8 w-8">
                                  <MoreVertical className="w-4 h-4" />
                                </Button>
                              </DropdownMenuTrigger>
                              <DropdownMenuContent align="end">
                                <DropdownMenuItem onClick={() => handleViewDetails(record)}>
                                  <Eye className="w-4 h-4 mr-2" />
                                  View Details
                                </DropdownMenuItem>
                                <DropdownMenuItem onClick={() => handleEdit(record)}>
                                  <Edit className="w-4 h-4 mr-2" />
                                  Edit Record
                                </DropdownMenuItem>
                                <DropdownMenuItem
                                  className="text-destructive"
                                  onClick={() => handleDeleteClick(record)}
                                >
                                  <Trash2 className="w-4 h-4 mr-2" />
                                  Delete
                                </DropdownMenuItem>
                              </DropdownMenuContent>
                            </DropdownMenu>
                          </div>
                        </TableCell>
                      </TableRow>
                    );
                  })}
                </TableBody>
              </Table>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Mark Attendance Dialog */}
      <Dialog open={isAddDialogOpen} onOpenChange={setIsAddDialogOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle>Mark Attendance</DialogTitle>
            <DialogDescription>
              Record attendance for a class on a specific date.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleAddSubmit}>
            <div className="grid gap-4 py-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="class">Class *</Label>
                  <Select
                    value={formData.class}
                    onValueChange={(value) => setFormData({ ...formData, class: value })}
                    required
                  >
                    <SelectTrigger id="class">
                      <SelectValue placeholder="Select class" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Grade 9 - English">Grade 9 - English</SelectItem>
                      <SelectItem value="Grade 10 - Mathematics">Grade 10 - Mathematics</SelectItem>
                      <SelectItem value="Grade 11 - Science">Grade 11 - Science</SelectItem>
                      <SelectItem value="Grade 12 - History">Grade 12 - History</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="teacher">Teacher *</Label>
                  <Select
                    value={formData.teacher}
                    onValueChange={(value) => setFormData({ ...formData, teacher: value })}
                    required
                  >
                    <SelectTrigger id="teacher">
                      <SelectValue placeholder="Select teacher" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Dr. Robert Anderson">Dr. Robert Anderson</SelectItem>
                      <SelectItem value="Prof. Maria Garcia">Prof. Maria Garcia</SelectItem>
                      <SelectItem value="Ms. Jennifer Lee">Ms. Jennifer Lee</SelectItem>
                      <SelectItem value="Mr. James Taylor">Mr. James Taylor</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
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
              <div className="grid grid-cols-4 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="totalStudents">Total *</Label>
                  <Input
                    id="totalStudents"
                    type="number"
                    required
                    value={formData.totalStudents}
                    onChange={(e) => setFormData({ ...formData, totalStudents: e.target.value })}
                    placeholder="30"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="present">Present *</Label>
                  <Input
                    id="present"
                    type="number"
                    required
                    value={formData.present}
                    onChange={(e) => setFormData({ ...formData, present: e.target.value })}
                    placeholder="28"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="absent">Absent *</Label>
                  <Input
                    id="absent"
                    type="number"
                    required
                    value={formData.absent}
                    onChange={(e) => setFormData({ ...formData, absent: e.target.value })}
                    placeholder="2"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="late">Late</Label>
                  <Input
                    id="late"
                    type="number"
                    value={formData.late}
                    onChange={(e) => setFormData({ ...formData, late: e.target.value })}
                    placeholder="0"
                  />
                </div>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsAddDialogOpen(false)}>
                Cancel
              </Button>
              <Button type="submit">
                <ClipboardCheck className="w-4 h-4 mr-2" />
                Mark Attendance
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Edit Attendance Dialog */}
      <Dialog open={isEditDialogOpen} onOpenChange={setIsEditDialogOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle>Edit Attendance Record</DialogTitle>
            <DialogDescription>
              Update the attendance record information.
            </DialogDescription>
          </DialogHeader>
          <form onSubmit={handleEditSubmit}>
            <div className="grid gap-4 py-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="edit-class">Class *</Label>
                  <Select
                    value={formData.class}
                    onValueChange={(value) => setFormData({ ...formData, class: value })}
                    required
                  >
                    <SelectTrigger id="edit-class">
                      <SelectValue placeholder="Select class" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Grade 9 - English">Grade 9 - English</SelectItem>
                      <SelectItem value="Grade 10 - Mathematics">Grade 10 - Mathematics</SelectItem>
                      <SelectItem value="Grade 11 - Science">Grade 11 - Science</SelectItem>
                      <SelectItem value="Grade 12 - History">Grade 12 - History</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-teacher">Teacher *</Label>
                  <Select
                    value={formData.teacher}
                    onValueChange={(value) => setFormData({ ...formData, teacher: value })}
                    required
                  >
                    <SelectTrigger id="edit-teacher">
                      <SelectValue placeholder="Select teacher" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="Dr. Robert Anderson">Dr. Robert Anderson</SelectItem>
                      <SelectItem value="Prof. Maria Garcia">Prof. Maria Garcia</SelectItem>
                      <SelectItem value="Ms. Jennifer Lee">Ms. Jennifer Lee</SelectItem>
                      <SelectItem value="Mr. James Taylor">Mr. James Taylor</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
              </div>
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
              <div className="grid grid-cols-4 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="edit-totalStudents">Total *</Label>
                  <Input
                    id="edit-totalStudents"
                    type="number"
                    required
                    value={formData.totalStudents}
                    onChange={(e) => setFormData({ ...formData, totalStudents: e.target.value })}
                    placeholder="30"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-present">Present *</Label>
                  <Input
                    id="edit-present"
                    type="number"
                    required
                    value={formData.present}
                    onChange={(e) => setFormData({ ...formData, present: e.target.value })}
                    placeholder="28"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-absent">Absent *</Label>
                  <Input
                    id="edit-absent"
                    type="number"
                    required
                    value={formData.absent}
                    onChange={(e) => setFormData({ ...formData, absent: e.target.value })}
                    placeholder="2"
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="edit-late">Late</Label>
                  <Input
                    id="edit-late"
                    type="number"
                    value={formData.late}
                    onChange={(e) => setFormData({ ...formData, late: e.target.value })}
                    placeholder="0"
                  />
                </div>
              </div>
            </div>
            <DialogFooter>
              <Button type="button" variant="outline" onClick={() => setIsEditDialogOpen(false)}>
                Cancel
              </Button>
              <Button type="submit">
                <Edit className="w-4 h-4 mr-2" />
                Update Record
              </Button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Delete Confirmation Dialog */}
      <AlertDialog open={isDeleteDialogOpen} onOpenChange={setIsDeleteDialogOpen}>
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Delete Attendance Record?</AlertDialogTitle>
            <AlertDialogDescription>
              This will permanently delete the attendance record for{" "}
              <span className="font-semibold">{selectedRecord?.class}</span> on{" "}
              <span className="font-semibold">{selectedRecord?.date}</span>. This action cannot be undone.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancel</AlertDialogCancel>
            <AlertDialogAction
              onClick={handleDelete}
              className="bg-destructive text-destructive-foreground hover:bg-destructive/90"
            >
              <Trash2 className="w-4 h-4 mr-2" />
              Delete
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>

      {/* View Details Dialog */}
      <Dialog open={isDetailsDialogOpen} onOpenChange={setIsDetailsDialogOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle className="flex items-center gap-2">
              <ClipboardCheck className="w-5 h-5" />
              Attendance Details
            </DialogTitle>
            <DialogDescription>
              Complete attendance record information
            </DialogDescription>
          </DialogHeader>
          <div className="py-4">
            {selectedRecord && (
              <div className="space-y-4">
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <p className="text-sm text-muted-foreground">Record ID</p>
                    <p className="font-mono font-medium">{selectedRecord.id}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Date</p>
                    <p className="font-medium">{selectedRecord.date}</p>
                  </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div>
                    <p className="text-sm text-muted-foreground">Class</p>
                    <p className="font-medium">{selectedRecord.class}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Teacher</p>
                    <p className="font-medium">{selectedRecord.teacher}</p>
                  </div>
                </div>
                <div className="grid grid-cols-4 gap-4">
                  <div>
                    <p className="text-sm text-muted-foreground">Total Students</p>
                    <p className="text-2xl font-bold">{selectedRecord.totalStudents}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Present</p>
                    <p className="text-2xl font-bold text-green-600">{selectedRecord.present}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Absent</p>
                    <p className="text-2xl font-bold text-red-600">{selectedRecord.absent}</p>
                  </div>
                  <div>
                    <p className="text-sm text-muted-foreground">Late</p>
                    <p className="text-2xl font-bold text-amber-600">{selectedRecord.late}</p>
                  </div>
                </div>
                <div>
                  <p className="text-sm text-muted-foreground">Attendance Rate</p>
                  <p className="text-3xl font-bold">
                    {selectedRecord.totalStudents > 0
                      ? ((selectedRecord.present / selectedRecord.totalStudents) * 100).toFixed(1)
                      : "0.0"}%
                  </p>
                </div>
                <div>
                  <p className="text-sm text-muted-foreground">Status</p>
                  <Badge
                    variant={selectedRecord.status === "Completed" ? "default" : "secondary"}
                    className={selectedRecord.status === "Completed" ? "bg-green-500" : ""}
                  >
                    {selectedRecord.status}
                  </Badge>
                </div>
              </div>
            )}
          </div>
          <DialogFooter>
            <Button variant="outline" onClick={() => setIsDetailsDialogOpen(false)}>
              Close
            </Button>
            {selectedRecord && (
              <Button onClick={() => {
                setIsDetailsDialogOpen(false);
                handleEdit(selectedRecord);
              }}>
                <Edit className="w-4 h-4 mr-2" />
                Edit Record
              </Button>
            )}
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </DashboardLayout>
  );
}

